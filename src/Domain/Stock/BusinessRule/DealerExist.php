<?php

namespace App\Domain\Stock\BusinessRule;

use App\Abstract\BusinessRuleAbstract;
use App\Constant\ValidationConstants;
use App\Domain\Dealer\Repository\DealerRepository;
use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockParameter;
use App\Exception\InvalidBusinessRulesException;

class DealerExist extends BusinessRuleAbstract
{
    public function apply(StockData $data): StockData
    {
        $repository = $this->container->get(DealerRepository::class);
        $result = $repository->getRowByCode($this->getDsn(), $data->getDealerCode());

        if ($result->getVisible() == 0 and !in_array($data->getDealerCode(), ValidationConstants::LK_DEFAULT_VALUE_ARRAY)) {
            throw new InvalidBusinessRulesException(
                sprintf('%s value not available', StockParameter::DEALER_CODE)
            );
        }

        if ($result->getDeleted() == 1) {
            throw new InvalidBusinessRulesException(sprintf('%s value not available', StockParameter::DEALER_CODE));
        }

        $data->setDealerId($result->getId());

        return parent::apply($data);
    }
}
