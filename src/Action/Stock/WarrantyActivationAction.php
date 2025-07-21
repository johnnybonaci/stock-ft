<?php

namespace App\Action\Stock;

use App\Abstract\WarrantyActivationActionAbstract;
use App\Domain\Stock\Data\StockBusinessRulesValidator;
use App\Domain\Stock\Service\BackendSecurityCommandService;
use App\Domain\Stock\Service\UpdateService;
use DI\Container;
use Pilot\Component\Renderers\StandardResponse;
use Psr\Http\Message\ServerRequestInterface;

/**
 * WarrantyActivationAction.
 */
final class WarrantyActivationAction extends WarrantyActivationActionAbstract
{

    public function __construct(
        Container $container,
        UpdateService $service,
        StandardResponse $standardOutput,
        BackendSecurityCommandService $backendCommandService,
        StockBusinessRulesValidator $stockBusinessRulesValidator,
    ) {
        parent::__construct($container, $service, $standardOutput, $backendCommandService, $stockBusinessRulesValidator );
    }

    protected function resolveDsn(ServerRequestInterface $request): string
    {
        return $request->getAttribute('dsn');
    }
}
