<?php

namespace App\Domain\StockImport\Service;

use App\Domain\StockImport\Data\StockImportEntity;
use App\Domain\StockImport\Repository\CreateRepository;

/**
 * Create Service.
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
     * Returns the newly created StockImport.
     *
     * @param string $dsn Name of instance
     * @param StockImportEntity $newStockImportEntity New StockImport
     *
     * @return StockImportEntity
     */
    public function create(string $dsn, StockImportEntity $newStockImportEntity): StockImportEntity
    {
        return $this->repository->create($dsn, $newStockImportEntity);
    }
}
