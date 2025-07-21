<?php

namespace App\Action\StockImport;

use App\Abstract\StockImportActionAbstract;
use App\Domain\StockImport\Service\ReadService;
use Pilot\Component\Renderers\StandardResponse;
use Pilot\Component\RequestParameter\Factories\RequestParameterFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * ReadAction.
 */
final class ReadAction extends StockImportActionAbstract
{
    /**
     * The constructor.
     *
     * @param ContainerInterface $container The container, for dependency injection
     * @param ReadService $service Service designed to create a given entity
     * @param StandardResponse $standardOutput standard output response
     */
    public function __construct(
        private ContainerInterface $container, // @phpstan-ignore-line
        private ReadService $service,
        private StandardResponse $standardOutput,
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
            $id = $request->getAttribute('id');
            $dsn = $request->getAttribute('dsn');
            
            $stockImport = $this->service->read($dsn, $id);

            $return = $this
                ->standardOutput
                ->setOutput(
                    true,
                    'StockImport read OK',
                    $stockImport->toArray()
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
