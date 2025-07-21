<?php

namespace App\Domain\Stock\BusinessRule;

use App\Abstract\BusinessRuleAbstract;
use App\Constant\ValidationConstants;
use App\Domain\ProductVehicle\Repository\ProductVehicleRepository;
use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockParameter;
use App\Exception\InvalidBusinessRulesException;

class ProductVehicleExist extends BusinessRuleAbstract
{
    public function apply(StockData $data): StockData
    {
        $repository = $this->container->get(ProductVehicleRepository::class);
        $result = $repository->getRowByCode($this->getDsn(), $data->getProductVehicleCode());

        if ($result->getVisible() == 0 and !in_array($data->getProductVehicleCode(), ValidationConstants::LK_DEFAULT_VALUE_ARRAY)) {
            throw new InvalidBusinessRulesException(
                sprintf('%s value not available', StockParameter::PRODUCT_VEHICLE_CODE)
            );
        }

        if ($result->getDeleted() == 1) {
            throw new InvalidBusinessRulesException(sprintf('%s value not available', StockParameter::PRODUCT_VEHICLE_CODE));
        }

        $data->setProductVehicleId($result->getId());

        return parent::apply($data);
    }
}
