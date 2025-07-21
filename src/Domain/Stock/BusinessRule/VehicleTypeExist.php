<?php

namespace App\Domain\Stock\BusinessRule;

use App\Abstract\BusinessRuleAbstract;
use App\Constant\ValidationConstants;
use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockParameter;
use App\Domain\VehicleType\Repository\VehicleTypeRepository;
use App\Exception\InvalidBusinessRulesException;

class VehicleTypeExist extends BusinessRuleAbstract
{
    public function apply(StockData $data): StockData
    {
        $repository = $this->container->get(VehicleTypeRepository::class);
        $result = $repository->readByCode($data->getVehicleTypeCode());

        if ($result->getVisible() == 0 and !in_array($data->getVehicleTypeCode(), ValidationConstants::LK_DEFAULT_VALUE_ARRAY)) {
            throw new InvalidBusinessRulesException(
                sprintf('%s value not available', StockParameter::VEHICLE_TYPE_CODE)
            );
        }

        if ($result->getDeleted() == 1) {
            throw new InvalidBusinessRulesException(sprintf('%s value not available', StockParameter::VEHICLE_TYPE_CODE));
        }

        $data->setVehicleTypeId($result->getId());

        return parent::apply($data);
    }
}
