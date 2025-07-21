<?php

namespace App\Action\Stock\Api;

use App\Abstract\StockActionAbstract;
use App\Domain\Stock\Data\StockParameter;
use App\Domain\Stock\Service\BackendSecurityCommandService;
use App\Domain\Stock\Service\DeleteService;
use Pilot\Component\Renderers\StandardResponse;
use Pilot\Component\RequestParameter\Factories\RequestParameterFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Pilot\Component\SessionManager\SessionManager;

/**
 * DeleteAction.
 */
final class DeleteApiAction extends StockActionAbstract
{
    /**
     * The constructor.
     *
     * @param ContainerInterface $container The container, for dependency injection
     * @param SessionManager $sessionManager The session Manager
     * @param DeleteService $service Service designed in order to delete a given entity
     * @param StandardResponse $standardOutput Standard response
     * @param BackendSecurityCommandService $backendCommandService commands permissions
     */
    public function __construct(
        private ContainerInterface $container, // @phpstan-ignore-line
        private SessionManager $sessionManager,
        private DeleteService $service,
        private StandardResponse $standardOutput,
        private BackendSecurityCommandService $backendCommandService,
    ) {
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
            $requestParameters = RequestParameterFactory::create($request);
            $id = $request->getAttribute('id');
            $dsn = (string)$this->sessionManager->getIdentity()->getInstance_dsn();

            $auditUserId = $requestParameters->get(StockParameter::AUDIT_USER_ID)
                ->required(sprintf("Field '%s' is required", StockParameter::AUDIT_USER_ID), false)
                ->getValue();


            $deletedstock = $this->service->softDelete($dsn, $id, $auditUserId);

            $return = $this
                ->standardOutput
                ->setOutput(
                    true,
                    'Stock delete OK',
                    $deletedstock->toArray()
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
        } catch (\App\Exception\SecurityCommandException $e) {
            $return = $this->standardOutput
                ->setOutput(
                    false,
                    $e->getMessage(),
                    $e->getContext(),
                    $e->getCode(),
                    $e->getSubcode()
                );
        } catch (\Pilot\Component\Sql\Exceptions\RecordNotFoundException $e) {
            $return = $this->standardOutput
                ->setOutput(
                    false,
                    $e->getMessage(),
                    [],
                    404,
                    $e->getSubcode()
                );
        } catch (\Throwable $th) {
            $return = $this->standardOutput
                ->setOutput(
                    false,
                    $th->getMessage(),
                    [
                        'file' => $th->getFile(),
                        'line' => $th->getLine(),
                    ],
                    $th->getCode(),
                    'unhandled_error'
                );
        }

        return $return->response($response);
    }
}
