<?php

namespace App\Action\Stock;

use App\Abstract\FindActionAbstract;
use App\Domain\Stock\Service\BackendSecurityCommandService;
use App\Domain\Stock\Service\FindService;
use Pilot\Component\Renderers\StandardResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Backend Find Action - DSN from request attributes
 */
final class FindAction extends FindActionAbstract
{
    public function __construct(
        ContainerInterface $container,
        FindService $service,
        StandardResponse $standardOutput,
        BackendSecurityCommandService $backendCommandService,
    ) {
        parent::__construct($container, $service, $standardOutput, $backendCommandService);
    }

    /**
     * Backend strategy: get DSN from request attributes
     */
    protected function resolveDsn(ServerRequestInterface $request): string
    {
        return $request->getAttribute('dsn');
    }
}
