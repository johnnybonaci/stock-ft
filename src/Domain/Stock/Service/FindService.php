<?php

namespace App\Domain\Stock\Service;

use App\Domain\Stock\Data\StockParameter;
use App\Domain\Stock\Repository\FindRepository;
use App\DTO\FinderDTO;
use Pilot\Component\Sql\DataCollection;

/**
 * Service.
 */
final class FindService
{
    /**
     * The constructor.
     *
     * @param FindRepository $repository The repository
     */
    public function __construct(
        private FindRepository $repository,
    ) {
    }

    /**
     * Returns a collection of StockData objects.
     *
     * @param string $dsn Name of Pilot's instance
     * @param FinderDTO $dto search filters, sorts and paging
     *
     * @return DataCollection
     */
    public function find(string $dsn, FinderDTO $dto): DataCollection
    {
        return $this->repository->find($dsn, $dto);
    }
    
    
    /**
     * Returns a collection of StockData objects.
     *
     * @param string $dsn Name of Pilot's instance
     * @param string $vin vin number
     *
     * @return DataCollection
     */
    public function findByVin(string $dsn, string $vin): DataCollection
    {
        $filters = [
            [
                'field' => StockParameter::VEHICLE_IDENTIFICATION_NUMBER,
                'operation' => '=',
                'value' => $vin,
            ],
            [
                'field' => StockParameter::DELETED,
                'operation' => '<>',
                'value' => 1,
            ]
        ];

        $finder = new FinderDTO(1, 1, [], $filters);

        return $this->repository->find($dsn, $finder);
    }
}
