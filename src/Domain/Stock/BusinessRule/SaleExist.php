<?php

namespace App\Domain\Stock\BusinessRule;

use App\Abstract\BusinessRuleAbstract;
use App\Domain\Sale\Repository\SaleRepository;
use App\Domain\Stock\Data\StockData;

class SaleExist extends BusinessRuleAbstract
{
    public function apply(StockData $data): StockData
    {
        if (!empty($data->getSaleId())) {
            $repository = $this->container->get(SaleRepository::class);
            $result = $repository->readByGuid($data->getSaleId());

            $data->setSaleProcessId($result->getId());
            $data->setSoldFlag(1);
            if (empty($data->getSoldDate())) {
                $data->setSoldDate($result->getSale_date());
            }
        }

        return parent::apply($data);
    }
}
