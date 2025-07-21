<?php

namespace App\Action\StockImport;

use App\Abstract\StockImportActionAbstract;
use App\Domain\StockImport\Data\StockImportEntity;
use App\Domain\StockImport\Service\CreateService;
use App\Domain\StockImport\Service\ImportSecurityCommandService;
use App\Exception\QueueManagerServiceException;
use App\Services\QueueManagerService;
use Pilot\Component\Renderers\StandardResponse;
use Pilot\Component\RequestParameter\Factories\RequestParameterFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * CreateAction.
 */
final class CreateAction extends StockImportActionAbstract
{
    /**
     * The constructor.
     *
     * @param ContainerInterface $container The container, for dependency injection
     * @param CreateService $service: Service designed to create a given entity
     * @param StandardResponse $standardOutput Standard response
     * @param ImportSecurityCommandService $backendCommandService commands permissions
     */
    public function __construct(
        private ContainerInterface $container, // @phpstan-ignore-line
        private CreateService $service,
        private StandardResponse $standardOutput,
        private ImportSecurityCommandService $backendCommandService,
    ) {
        parent::__construct($container);
    }

    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     * @param array $args Arguments
     *
     * @return ResponseInterface The response
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $dsn = $request->getAttribute('dsn');
            $requestParameters = RequestParameterFactory::create($request);

            $stockImportEntity = $this->loadEntityFromParamsOrFail($dsn, $requestParameters);

            $createdStockImport = $this->service->create($dsn, $stockImportEntity);

            // Una vez que se guarda se debe enviar a SQS
            $this->publishInSQS($dsn, $createdStockImport);

            $return = $this->standardOutput->setOutput(
                true,
                'StockImport created OK',
                $createdStockImport->toArray(),
                201,
                'stockimport_created_ok'
            );
        } catch (\App\Exception\SecurityCommandException|QueueManagerServiceException $e) {
            $return = $this->standardOutput
                ->setOutput(
                    false,
                    $e->getMessage(),
                    $e->getContext(),
                    $e->getCode(),
                    $e->getSubcode()
                );
        } catch (\Pilot\Component\RequestParameter\Exceptions\RequiredParameterException $e) {
            $return = $this->standardOutput
                ->setOutput(
                    false,
                    $e->getMessage(),
                    [],
                    400,
                    $e->getSubcode()
                );
        } catch (\Throwable $th) {
            $return = $this->standardOutput->setOutput(
                false,
                $th->getMessage(),
                [
                    'file' => $th->getFile(),
                    'line' => $th->getLine(),
                    'params' => $request->getParsedBody(),
                ],
                $th->getCode(),
                'unhandled_error'
            );
        }

        return $return->response($response);
    }

    /**
     * Funcion privada para enviar conectarse al servicio de QueueManagerService y publicar en SQS.
     *
     * @param string $dsn
     * @param StockImportEntity $entity
     * 
     * @return void
     *
     * @throws QueueManagerServiceException
     */
    private function publishInSQS(string $dsn, StockImportEntity $entity): void
    {
        try {
            $msgBody = [
                'path' => $entity->getInput_file_s3_key(),
                'dsn' => $dsn,
                'audit_user_id' => $entity->getRequest_user_id(),

                'on-error-notify-user' => [
                    "title" => "Error en el proceso de importacion de stock",
                    "message" => "Fallo el proceso de encolamiento en SQS para importacion de stock",
                    "from_user_id" => $entity->getRequest_user_id(),
                    "to_user_id" => $entity->getRequest_user_id(),
                    "dsn" =>  $dsn
                ]
            ];

            $payload = [
                'instance_code' => $dsn,
                'topic_name' => 'hub_factory_stock',
                'event_name' => 'hub_factory_stock_file_import',
                'message_body' => $msgBody,
            ];

            // # agrega el procesamiento de archivos en la cola de trabajo
            $resp = $this->getContainer()->get(QueueManagerService::class)->enqueue($payload);

        } catch (\Throwable $th) {
            throw new QueueManagerServiceException($th->getMessage(), $payload ?? [], 500, 'send_sqs_event_failed');
        }
    }
}
