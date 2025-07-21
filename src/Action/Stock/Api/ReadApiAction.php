<?php

namespace App\Action\Stock\Api;

use App\Abstract\ReadActionAbstract;
use App\Domain\Stock\Service\BackendSecurityCommandService;
use App\Domain\Stock\Service\ReadService;
use Pilot\Component\Renderers\StandardResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Pilot\Component\SessionManager\SessionManager;

/**
 * ReadAction.
 */
final class ReadApiAction extends ReadActionAbstract
{

    public function __construct(
        ContainerInterface $container,
        private SessionManager $sessionManager,
        ReadService $service,
        StandardResponse $standardOutput,
        BackendSecurityCommandService $backendCommandService,
    ) {
        parent::__construct($container, $service, $standardOutput, $backendCommandService);
    }


    /**
     * API strategy: get DSN from session manager
     */
    protected function resolveDsn(ServerRequestInterface $request): string
    {
        return (string)$this->sessionManager->getIdentity()->getInstance_dsn();
    }
}
