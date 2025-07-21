<?php

use App\Domain\Dealer\Repository\DealerRepository;
use App\Domain\ProductVehicle\Repository\ProductVehicleRepository;
use App\Domain\Sale\Repository\SaleRepository;
use Pilot\Component\Renderers\JsonRenderer;
use Nyholm\Psr7\Factory\Psr17Factory;
use Pilot\CacheManager\CacheManagerFactory;
use Pilot\CacheManager\Entities\Cache;
use Pilot\Component\ErrorHandler\ErrorHandler;
use Pilot\Component\ErrorHandler\Renders\HtmlErrorRender;
use Pilot\Component\ErrorHandler\Renders\JsonErrorRender;
use Pilot\Component\Logger\Logger;
use Pilot\Component\Security\Middlewares\ModuleSecurityMiddleware;
use Pilot\Component\Security\Security;
use Pilot\Component\SessionManager\SessionManager;
use Pilot\Component\VarEnvironment\Factories\VarEnvironmentFactory;
use Pilot\Component\VarEnvironment\VarEnvironment;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Selective\BasePath\BasePathMiddleware;
use Selective\Validation\Encoder\JsonEncoder;
use Selective\Validation\Middleware\ValidationExceptionMiddleware;
use Selective\Validation\Transformer\ErrorDetailsResultTransformer;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteParserInterface;
use Slim\Middleware\ErrorMiddleware;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;
use Pilot\Component\Sql\Core\Sql;
use Pilot\Component\Sql\SqlFactory;
use Pilot\Component\MicroserviceClient\Factories\MicroserviceClientFactory;
use Pilot\Component\MicroserviceClient\MicroserviceClient;
use Pilot\Component\SessionManager\Factories\SessionManagerFactory;
use Pkerrigan\Xray\Trace;
use App\Domain\Stock\Service\BackendSecurityCommandService;
use App\Domain\StockStatus\Repository\StockStatusRepository;
use App\Domain\VehicleType\Repository\VehicleTypeRepository;
use App\Services\QueueManagerService;

return [
    // Application settings
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    App::class => function (ContainerInterface $container) {

        $app = AppFactory::createFromContainer($container);

        // Register routes
        (require __DIR__ . '/routes.php')($app);

        // Register middleware
        (require __DIR__ . '/middleware.php')($app);

        return $app;
    },

        // HTTP factories
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    ServerRequestFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    StreamFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    UploadedFileFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    UriFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

        // The Slim RouterParser
    RouteParserInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getRouteCollector()->getRouteParser();
    },

        // The logger factory
    Logger::class => function (ContainerInterface $container): Logger {
        $logger = Logger::getInstance();

        // Setamos app name, tipo de logger a utilizar (Cloudwatch) y su configuracion
        $logger->setApp_name($container->get('settings')['application']['app_name']);
        $logger->setHandler('cloudwatch');
        $logger->setHandlerConfig([
            'aws' => [
                'version' => 'latest',
                'region' => VarEnvironmentFactory::create()->get('GLOBAL_AWS_CLOUDWATCH_REGION'),
                'credentials' => [
                    'key' => VarEnvironmentFactory::create()->get('GLOBAL_AWS_SDK_CLOUDWATCH_KEY'),
                    'secret' => VarEnvironmentFactory::create()->get('GLOBAL_AWS_SDK_CLOUDWATCH_SECRET'),
                ],
            ],
            'group_name' => VarEnvironmentFactory::create()->get('GLOBAL_AWS_CLOUDWATCH_LOG_GROUP_NAME'),
            'stream_name' => '', // project/level/yyyy-mm-dd ej: crm-web/critical/2023-05-30
        ]);
        return $logger;
    },

    JsonRenderer::class => function (ContainerInterface $container) {
        return new JsonRenderer();
    },

    ErrorHandler::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        return new ErrorHandler(
            $app->getCallableResolver(),
            $container->get(ResponseFactoryInterface::class),
            $container->get(Logger::class)
        );
    },

    BasePathMiddleware::class => function (ContainerInterface $container) {
        return new BasePathMiddleware($container->get(App::class));
    },

    ValidationExceptionMiddleware::class => function (ContainerInterface $container) {
        return new ValidationExceptionMiddleware(
            $container->get(ResponseFactoryInterface::class),
            new ErrorDetailsResultTransformer(),
            new JsonEncoder()
        );
    },

    ErrorMiddleware::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['DefaultErrorHandler'];
        $app = $container->get(App::class);

        $logger = $container->get(Logger::class);

        if (strtolower(getenv('APP_ENV')) == 'local') {
            $errorMiddleware = new WhoopsMiddleware(['enable' => true]);
        } else {
            $errorMiddleware = new ErrorMiddleware(
                $app->getCallableResolver(),
                $app->getResponseFactory(),
                (bool) $settings['display_error_details'],
                (bool) $settings['log_errors'],
                (bool) $settings['log_error_details'],
                $logger
            );

            $errorMiddleware->setDefaultErrorHandler($container->get(ErrorHandler::class));

            $errorHandler = $errorMiddleware->getDefaultErrorHandler();
            $errorHandler->registerErrorRenderer('text/html', HtmlErrorRender::class);
            $errorHandler->registerErrorRenderer('application/json', JsonErrorRender::class);
        }

        return $errorMiddleware;
    },

    Application::class => function (ContainerInterface $container) {
        $application = new Application();
        $application->getDefinition()->addOption(
            new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'development')
        );

        foreach ($container->get('settings')['commands'] as $class) {
            $application->add($container->get($class));
        }
        return $application;
    },

    // Acceso a variables de ambiente
    VarEnvironment::class => function (ContainerInterface $container): VarEnvironment {
        return VarEnvironmentFactory::create();
    },


    Sql::class => function (ContainerInterface $container): Sql {
        if (!empty($container->has('dsn'))) {
            return SqlFactory::create()->open((string)$container->get('dsn'), 'hub');
        }

        return SqlFactory::create();
    },

    SessionManager::class => function () {
        return SessionManagerFactory::create();
    },

    MicroserviceClient::class => function (ContainerInterface $container): MicroserviceClient {
        return MicroserviceClientFactory::create(
            $container->get(VarEnvironment::class),
            null,
            Trace::getInstance()
        );
    },

    Cache::class => function () {
        return CacheManagerFactory::BaasCache();
    },

    // This is used for public API endpoints that require authorization throw access_token
    Security::class => function (ContainerInterface $container): Security {
        return new Security($container->get(MicroserviceClient::class));
    },
    
    ModuleSecurityMiddleware::class => function (ContainerInterface $container) {
        $responseFactory = $container->get(ResponseFactoryInterface::class);
        $session = $container->get(SessionManager::class);
        $security = $container->get(Security::class);

        $settings = $container->get('settings');

        return new ModuleSecurityMiddleware(
            $responseFactory,
            $session,
            $security,
            $settings['ModuleSecurityMiddleware']['module_code']
        );
    },

    BackendSecurityCommandService::class => function (ContainerInterface $container) {
        return new BackendSecurityCommandService(new Security($container->get(MicroserviceClient::class)));
    },


    QueueManagerService::class => function (ContainerInterface $container) {
        return new QueueManagerService($container, $container->get(MicroserviceClient::class));
    },

    \App\Domain\Stock\Repository\FindRepository::class => function (ContainerInterface $container) {
        return new \App\Domain\Stock\Repository\FindRepository($container);
    },
    DealerRepository::class => function (ContainerInterface $container) {
        return new DealerRepository($container->get(MicroserviceClient::class));
    },
    ProductVehicleRepository::class => function (ContainerInterface $container) {
        return new ProductVehicleRepository($container->get(MicroserviceClient::class));
    },
    SaleRepository::class => function (ContainerInterface $container) {
        return new SaleRepository($container);
    },
    StockStatusRepository::class => function (ContainerInterface $container) {
        return new StockStatusRepository($container->get(MicroserviceClient::class), $container);
    },
    VehicleTypeRepository::class => function (ContainerInterface $container) {
        return new VehicleTypeRepository($container->get(MicroserviceClient::class), $container);
    },
];
