<?php

namespace App\Domain\Stock\BusinessRule;

use App\Abstract\BusinessRuleAbstract;
use App\Constant\ValidationConstants;
use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockParameter;
use App\Domain\StockStatus\Repository\StockStatusRepository;
use App\Exception\InvalidBusinessRulesException;

class StockStatusExist extends BusinessRuleAbstract
{
    public function apply(StockData $data): StockData
    {
        $repository = $this->container->get(StockStatusRepository::class);
        $result = $repository->readByCode($data->getStatusCode());

        if ($result->getVisible() == 0 and !in_array($data->getStatusCode(), ValidationConstants::LK_DEFAULT_VALUE_ARRAY)) {
            throw new InvalidBusinessRulesException(
                sprintf('%s value not available', StockParameter::STATUS_CODE)
            );
        }

        if ($result->getDeleted() == 1) {
            throw new InvalidBusinessRulesException(sprintf('%s value not available', StockParameter::STATUS_CODE));
        }

        $data->setStatusId($result->getId());

        return parent::apply($data);
    }
}
