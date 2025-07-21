<?php

namespace App\Abstract;

use App\Abstract\StockActionAbstract;
use App\Domain\Stock\Service\BackendSecurityCommandService;
use App\Domain\Stock\Service\ReadService;
use Pilot\Component\Renderers\StandardResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * ReadAction.
 */
abstract class ReadActionAbstract extends StockActionAbstract
{
    public function __construct(
        private ContainerInterface $container,
        private ReadService $service,
        private StandardResponse $standardOutput,
        private BackendSecurityCommandService $backendCommandService,
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
            $id = $request->getAttribute('id');
            $dsn = $this->resolveDsn($request);

            $stock = $this->service->read($dsn, $id);

            $return = $this
                ->standardOutput
                ->setOutput(
                    true,
                    'Stock read OK',
                    $stock->toArray()
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
    /**
     * DSN resolution
     */
    abstract protected function resolveDsn(ServerRequestInterface $request): string;
}
