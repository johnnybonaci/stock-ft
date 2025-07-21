<?php

namespace App\Action\Stock;

use App\Abstract\ReadActionAbstract;
use App\Domain\Stock\Service\BackendSecurityCommandService;
use App\Domain\Stock\Service\ReadService;
use Pilot\Component\Renderers\StandardResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * ReadAction.
 */
final class ReadAction extends ReadActionAbstract
{
    public function __construct(
        ContainerInterface $container,
        ReadService $service,
        StandardResponse $standardOutput,
        BackendSecurityCommandService $backendCommandService,
    ) {
        parent::__construct($container, $service, $standardOutput, $backendCommandService);
    }


    protected function resolveDsn(ServerRequestInterface $request): string
    {
        return $request->getAttribute('dsn');
    }
}
