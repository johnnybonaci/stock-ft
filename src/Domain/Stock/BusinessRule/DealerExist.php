<?php

namespace App\Domain\Stock\BusinessRule;

use App\Abstract\BusinessRuleAbstract;
use App\Constant\ValidationConstants;
use App\Domain\Dealer\Repository\DealerRepository;
use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockParameter;
use App\Domain\Stock\Data\StockErrorCodes;
use App\Exception\InvalidBusinessRulesException;

class DealerExist extends BusinessRuleAbstract
{
    public function apply(StockData $data): StockData
    {
        $repository = $this->container->get(DealerRepository::class);
        $result = $repository->getRowByCode($this->getDsn(), $data->getDealerCode());

        if ($result->getVisible() == 0 and !in_array($data->getDealerCode(), ValidationConstants::LK_DEFAULT_VALUE_ARRAY)) {
            $error = StockErrorCodes::getErrorMessage(StockErrorCodes::E_200_107);
            throw new InvalidBusinessRulesException(
                $error['message'],
                [],
                $error['code'],
                $error['subcode']
            );
        }

        if ($result->getDeleted() == 1) {
            $error = StockErrorCodes::getErrorMessage(StockErrorCodes::E_200_107);
            throw new InvalidBusinessRulesException(
                $error['message'],
                [],
                $error['code'],
                $error['subcode']
            );
        }

        $data->setDealerId($result->getId());

        return parent::apply($data);
    }
}
