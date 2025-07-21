<?php

namespace App\Action\Stock;

use App\Abstract\StockActionAbstract;
use App\Domain\Stock\Service\CacheService;
use Pilot\Component\Renderers\StandardResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * DeleteCacheDsnAction.
 */
final class DeleteCacheDsnAction extends StockActionAbstract
{
    /**
     * The constructor.
     *
     * @param StandardResponse $standardOutput
     * @param ContainerInterface $container The container, for dependency injection
     * @param CacheService $service Service designed to create a given entity
     */
    public function __construct(
        private ContainerInterface $container, // @phpstan-ignore-line
        private StandardResponse $standardOutput,
        private CacheService $service,
    ) {
    }

    /**
     * The action itself.
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
            $this->service->deleteByDsn($dsn);
            $result = $this->standardOutput->setOutput(true, 'cache_dsn_deleted_ok', [], 200, 'cache_dsn_deleted_ok');
        } catch (\Throwable $ex) {
            $result = $this->standardOutput->setOutput(false, $ex->getMessage(), [], $ex->getCode(), 'unhandled_error');
        }

        return $result->response($response);
    }
}
