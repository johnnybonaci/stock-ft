<?php

namespace App\Domain\Stock\BusinessRule;

use App\Abstract\BusinessRuleAbstract;
use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockParameter;
use App\Domain\Stock\Repository\FindRepository;
use App\DTO\FinderDTO;
use App\Exception\InvalidBusinessRulesException;

class StockExist extends BusinessRuleAbstract
{
    public function apply(StockData $data): StockData
    {
        $filters1[] = [
            'field' => StockParameter::FACTORY_ORDER_NUMBER,
            'operation' => '=',
            'value' => $data->getFactoryOrderNumber(),
        ];
        $filters2[] = [
            'field' => StockParameter::VEHICLE_IDENTIFICATION_NUMBER,
            'operation' => '=',
            'value' => $data->getVehicleIdentificationNumber(),
        ];

        if ($data->getId() !== null) {
            $filter = [
                'field' => StockParameter::ID,
                'operation' => '<>',
                'value' => $data->getId(),
            ];

            $filters1[] = $filter;
            $filters2[] = $filter;
        }

        $filter_deleted = [
            'field' => StockParameter::DELETED,
            'operation' => '<>',
            'value' => 1,
        ];
        $filters1[] = $filter_deleted;
        $filters2[] = $filter_deleted;

        $finder1 = new FinderDTO(1, 1, [], $filters1);
        $finder2 = new FinderDTO(1, 1, [], $filters2);

        $repository = $this->container->get(FindRepository::class);

        $result = $repository->find($this->getDsn(), $finder1);
        if ($result->getTotalRows() > 0) {
            throw new InvalidBusinessRulesException(
                sprintf(
                    '%s value already exists in DataBase',
                    StockParameter::FACTORY_ORDER_NUMBER
                )
            );
        }

        $result = $repository->find($this->getDsn(), $finder2);
        if ($result->getTotalRows() > 0) {
            throw new InvalidBusinessRulesException(
                sprintf(
                    '%s value already exists in DataBase',
                    StockParameter::VEHICLE_IDENTIFICATION_NUMBER
                )
            );
        }

        return parent::apply($data);
    }
}
