<?php

namespace App\Domain\StockImport\Repository;

use App\Abstract\StockImportRepositoryAbstract;
use App\Domain\StockImport\Data\StockImportEntity;

/**
 * Repository.
 */
final class CreateRepository extends StockImportRepositoryAbstract
{
    /**
     * Create's a new StockImport.
     *
     * @param  StockImportEntity $newStockImport New StockImport
     * @param string $dsn
     *
     * @return StockImportEntity
     */
    public function create(string $dsn, StockImportEntity $newStockImport): StockImportEntity
    {
        $model = $this->getModel($dsn);

        // @phpstan-ignore-next-line
        return $model->create($newStockImport);
    }
}
