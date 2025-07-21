<?php

namespace App\Domain\Stock\Data;

use App\Domain\Stock\BusinessRule\DealerExist;
use App\Domain\Stock\BusinessRule\DealerInvoiceDateValidator;
use App\Domain\Stock\BusinessRule\DealerInvoiceNumberValidator;
use App\Domain\Stock\BusinessRule\ProductVehicleExist;
use App\Domain\Stock\BusinessRule\SaleExist;
use App\Domain\Stock\BusinessRule\StockExist;
use App\Domain\Stock\BusinessRule\StockStatusExist;
use App\Domain\Stock\BusinessRule\VehicleTypeExist;
use App\Domain\Stock\BusinessRule\VinValidator;
use App\Domain\Stock\BusinessRule\WarrantyDateValidator;
use Psr\Container\ContainerInterface;

class StockBusinessRulesValidator
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    /**
     * Respetar el orden de ejecución.
     *
     * @param StockData $stock
     */
    public function createValidate(StockData $stock): StockData
    {
        $stock = $this->genericValidate($stock);

        return $stock;
    }

    /**
     * Respetar el orden de ejecución.
     *
     * @param StockData $stock
     */
    public function updateValidate(StockData $stock): StockData
    {
        return $stock;
    }

    /**
     * The `genericValidate` function in PHP validates various aspects of a sale object and sets flags
     * based on certain conditions.
     *
     * @param StockData $stock
     *
     * @return StockData the function `genericValidate` is returning an instance of the `StockData` class
     * after performing various validation checks and setting certain flags on the sale data object
     */
    private function genericValidate(StockData $stock): StockData
    {
        // Se valida el los campos segun reglas de negocio.
        $dealerExist = new DealerExist($this->container);
        $productVehicleExist = new ProductVehicleExist($this->container);
        $saleExist = new SaleExist($this->container);
        $stockExist = new StockExist($this->container);
        $stockStatusExist = new StockStatusExist($this->container);
        $vehicleTypeExist = new VehicleTypeExist($this->container);

        $vinValidator = new VinValidator($this->container);
        $invoiceDateValidator = new DealerInvoiceDateValidator($this->container);
        $invoiceNumberValidator = new DealerInvoiceNumberValidator($this->container);

        $warrantyDateValidator = new WarrantyDateValidator($this->container);

        $dealerExist
            ->addRule($productVehicleExist)
            ->addRule($vehicleTypeExist)
            ->addRule($saleExist)
            ->addRule($stockExist)
            ->addRule($stockStatusExist)
            ->addRule($vinValidator)
            ->addRule($invoiceDateValidator)
            ->addRule($invoiceNumberValidator)
            ->addRule($warrantyDateValidator);

        $stock = $dealerExist->apply($stock);

        return $stock;
    }
}
