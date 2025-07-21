<?php

namespace App\Domain\StockImport\Repository;

use App\Abstract\StockImportRepositoryAbstract;
use App\Domain\StockImport\Data\StockImportEntity;

/**
 * Repository.
 */
final class ReadRepository extends StockImportRepositoryAbstract
{
    /**
     * Read StockImport.
     *
     * @param string $dsn data source name (~instance)
     * @param int $id StockImport id
     *
     * @return StockImportEntity An instance of StockImportEntity representing the requested StockImport
     */
    public function read(string $dsn, int $id): StockImportEntity
    {
        $model = $this->getModel($dsn);

        // @phpstan-ignore-next-line
        return $model->readOrFail($id);
    }
}
