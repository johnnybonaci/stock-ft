<?php

namespace App\Domain\Stock\Service;

use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockEntity;
use App\Domain\Stock\Repository\CreateRepository;

/**
 * Import Service.
 */
final class CreateService
{
    /**
     * The constructor.
     *
     * @param CreateRepository $repository The repository
     */
    public function __construct(
        private CreateRepository $repository,
    ) {
    }

    /**
     * Returns the newly created Stock.
     *
     * @param string $dsn Name of instance
     * @param StockData $stockData New Stock
     *
     * @return StockEntity
     */
    public function create(string $dsn, StockData $stockData): StockEntity
    {
        $newStockEntity = $this->repository->createEmptyEntity($dsn);
        $newStockEntity->loadFromState($stockData->toArray());

        return $this->repository->create($dsn, $newStockEntity);
    }
}
