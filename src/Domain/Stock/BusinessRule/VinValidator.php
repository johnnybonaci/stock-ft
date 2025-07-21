<?php

namespace App\Domain\Stock\BusinessRule;

use App\Abstract\BusinessRuleAbstract;
use App\Constant\ValidationConstants;
use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockParameter;
use App\Exception\InvalidBusinessRulesException;

class VinValidator extends BusinessRuleAbstract
{
    public function apply(StockData $data): StockData
    {
        $vin = (string)$data->getVehicleIdentificationNumber();

        if (!ctype_alnum($vin)) {
            throw new InvalidBusinessRulesException(
                sprintf('%s field not valid. Must contain letters (a-z) and digits (0-9)', StockParameter::VEHICLE_IDENTIFICATION_NUMBER)
            );
        }

        if ($data->getVehicleTypeCode() == ValidationConstants::VEHICLE_TYPE_CAR) {
            if (strlen($vin) < 17) {
                throw new InvalidBusinessRulesException(
                    sprintf('%s field not valid. Must contain 17 characters', StockParameter::VEHICLE_IDENTIFICATION_NUMBER)
                );
            }
        }

        if ($data->getVehicleTypeCode() == ValidationConstants::VEHICLE_TYPE_YACHT) {
            if (strlen($vin) < 12) {
                throw new InvalidBusinessRulesException(
                    sprintf('%s field not valid. Must contain 17 characters', StockParameter::VEHICLE_IDENTIFICATION_NUMBER)
                );
            }
        }

        return parent::apply($data);
    }
}
