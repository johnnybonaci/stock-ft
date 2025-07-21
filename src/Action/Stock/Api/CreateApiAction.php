<?php

namespace App\Action\Stock\Api;

use App\Abstract\CreateActionAbstract;
use App\Domain\Stock\Data\StockBusinessRulesValidator;
use App\Domain\Stock\Service\BackendSecurityCommandService;
use App\Domain\Stock\Service\CreateService;
use DI\Container;
use Pilot\Component\Renderers\StandardResponse;
use Psr\Http\Message\ServerRequestInterface;
use Pilot\Component\SessionManager\SessionManager;

/**
 * CreateAction.
 */
final class CreateApiAction extends CreateActionAbstract
{

    public function __construct(
            Container $container,
            private SessionManager $sessionManager,
            CreateService $service,
            StandardResponse $standardOutput,
            BackendSecurityCommandService $backendCommandService,
            StockBusinessRulesValidator $stockBusinessRulesValidator,
    ) {
        parent::__construct($container, $service, $standardOutput, $backendCommandService, $stockBusinessRulesValidator );
    }

    /**
     * API strategy: get DSN from session manager
     */
    protected function resolveDsn(ServerRequestInterface $request): string
    {
        return (string)$this->sessionManager->getIdentity()->getInstance_dsn();
    }
}
