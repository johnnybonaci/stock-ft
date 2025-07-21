<?php

namespace App\Action\Stock;

use App\Abstract\CreateActionAbstract;
use App\Domain\Stock\Data\StockBusinessRulesValidator;
use App\Domain\Stock\Service\BackendSecurityCommandService;
use App\Domain\Stock\Service\CreateService;
use DI\Container;
use Pilot\Component\Renderers\StandardResponse;
use Psr\Http\Message\ServerRequestInterface;

/**
 * CreateAction.
 */
final class CreateAction extends CreateActionAbstract
{

    public function __construct(
        private Container $container,
        private CreateService $service,
        private StandardResponse $standardOutput,
        private BackendSecurityCommandService $backendCommandService,
        private StockBusinessRulesValidator $stockBusinessRulesValidator,
    ) {
        parent::__construct($container, $service, $standardOutput, $backendCommandService, $stockBusinessRulesValidator );
    }

    protected function resolveDsn(ServerRequestInterface $request): string
    {
        return $request->getAttribute('dsn');
    }

}
