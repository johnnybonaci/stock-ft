<?php

use Slim\App;
use Pilot\Component\RequestParameter\Middlewares\ValidateInputParameterMiddleware;
use Pilot\Component\XrayMiddleware\XrayMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Selective\BasePath\BasePathMiddleware;
use Selective\Validation\Middleware\ValidationExceptionMiddleware;

return function (App $app) {
    // # FIFO pipeline

    // # aqui esta cerca de la aplicacion. dentro del anillo. Lo ultimo que se ejecuta antes que la app

    $app->addBodyParsingMiddleware();
    $app->add(ValidationExceptionMiddleware::class);
    $app->addRoutingMiddleware();
    $app->add(BasePathMiddleware::class);

    $app->add(ValidateInputParameterMiddleware::class);  // # realiza un control genérico de parámetros entrantes

    $app->add(XrayMiddleware::class); // # requiere de atributos de JwtAuthentication

    // Define Custom Error Handler
    $customErrorHandler = function (
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ) use ($app) {
        $payload = [
            'error' => $exception->getMessage(),
            'code' => 400
        ];

        $response = $app->getResponseFactory()->createResponse();
        $response->getBody()->write(
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        );

        return $response->withAddedHeader('Content-Type', 'application/json')->withStatus(400, 'Bad request');
    };

    // Add Error Middleware
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setDefaultErrorHandler($customErrorHandler);

    // # Esto es mas cerca de cliente, es lo que se ejecuta primero al entrar el request,
    // y lo ultimo al salir el response
};
