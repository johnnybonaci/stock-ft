<?php

// Define app routes

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

// Backend
use App\Action\Stock\CreateAction;
use App\Action\Stock\ReadAction;
use App\Action\Stock\UpdateAction;
use App\Action\Stock\DeleteAction;
use App\Action\Stock\FindAction;
use App\Action\Stock\DeleteCacheAllAction;
use App\Action\Stock\DeleteCacheDsnAction;
use App\Action\Stock\UpsertAction;
use App\Action\Stock\WarrantyActivationAction;
use App\Action\StockImport\CreateAction as StockImportCreateAction;
use App\Action\StockImport\FindAction as StockImportFindAction;
use App\Action\StockImport\ReadAction as StockImportReadAction;
//Api
use App\Action\Stock\Api\CreateApiAction;
use App\Action\Stock\Api\DeleteApiAction;
use App\Action\Stock\Api\FindApiAction;
use App\Action\Stock\Api\ReadApiAction;
use App\Action\Stock\Api\UpdateApiAction;
use App\Action\Stock\Api\UpsertApiAction;
use App\Action\Stock\Api\WarrantyActivationApiAction;
//Security
use Pilot\Component\Security\Middlewares\ModuleSecurityMiddleware;
use Pilot\Component\SessionManager\SessionManagerMiddleware;

return function (App $app) {
    $app->group('/{dsn}/api/stocks', function (RouteCollectorProxy $app) {
        $app->get('', FindApiAction::class)->setName('findApiStock');
        $app->post('', CreateApiAction::class)->setName('createApiStock');
        $app->put('/{id}', callable: UpdateApiAction::class)->setName('updateApiStock');
        $app->get('/{id}', ReadApiAction::class)->setName('readApiStock');
        $app->delete('/{id}', DeleteApiAction::class)->setName('deleteApiStock');
        $app->put('/warranty-activation/{id}', WarrantyActivationApiAction::class)->setName('warrantyApiActivation');
        $app->post('/upsert', UpsertApiAction::class)->setName('upsertApiStock');

    })
    ->add(ModuleSecurityMiddleware::class)
    ->add(SessionManagerMiddleware::class);

    $app->group('/{dsn}/stocks', function (RouteCollectorProxy $app) {
        $app->post('', CreateAction::class)->setName('createStock');
        $app->post('/upsert', UpsertAction::class)->setName('upsertStock');
        $app->get('/{id}', ReadAction::class)->setName('readStock');
        $app->put('/{id}', callable: UpdateAction::class)->setName('updateStock');
        $app->put('/warranty-activation/{id}', WarrantyActivationAction::class)->setName('warrantyActivation');
        $app->delete('/{id}', DeleteAction::class)->setName('deleteStock');
        $app->get('', FindAction::class)->setName('findStock');
    });

    $app->group('/{dsn}/imports', function (RouteCollectorProxy $app) {
        $app->post('', StockImportCreateAction::class)->setName('createStockImport');
        $app->get('/{id}', StockImportReadAction::class)->setName('readStockImport');
        $app->get('', StockImportFindAction::class)->setName('findStockImport');
    });

    $app->group('/cache', function (RouteCollectorProxy $app) {
        $app->delete('', DeleteCacheAllAction::class)->setName('deleteCacheAllStock');
        $app->delete('/{dsn}', DeleteCacheDsnAction::class)->setName('deleteCacheDsnStock');
    });
};
