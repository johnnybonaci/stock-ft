<?php

namespace App\Domain\StockImport\Service;

use App\Domain\StockImport\Data\StockImportEntity;
use App\Domain\StockImport\Repository\ReadRepository;

/**
 * Read Service.
 */
final class ReadService
{
    /**
     * The constructor.
     *
     * @param ReadRepository $repository The repository
     */
    public function __construct(
        private ReadRepository $repository,
    ) {
    }

    /**
     * Returns the StockImport as a StockImportEntity object.
     *
     * @param string $dsn data source name (~instance)
     * @param int $id Currencie's Id
     *
     * @return StockImportEntity
     */
    public function read(string $dsn, int $id): StockImportEntity
    {
        return $this->repository->read($dsn, $id);
    }
}
