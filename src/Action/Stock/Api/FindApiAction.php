<?php

namespace App\Action\Stock\Api;

use App\Abstract\FindActionAbstract;
use App\Domain\Stock\Service\BackendSecurityCommandService;
use App\Domain\Stock\Service\FindService;
use Pilot\Component\Renderers\StandardResponse;
use Pilot\Component\SessionManager\SessionManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * API Find Action - DSN from session manager
 */
final class FindApiAction extends FindActionAbstract
{
    public function __construct(
        ContainerInterface $container,
        private SessionManager $sessionManager,
        FindService $service,
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
