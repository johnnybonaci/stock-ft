<?php

namespace App\Domain\Stock\Repository;

use App\Abstract\StockRepositoryAbstract;
use App\Domain\Stock\Data\StockEntity;

/**
 * Repository.
 */
final class ReadRepository extends StockRepositoryAbstract
{
    /**
     * Read Stock.
     *
     * @param string $dsn data source name (~instance)
     * @param int $id Stock id
     *
     * @return StockEntity An instance of StockEntity representing the requested Stock
     */
    public function read(string $dsn, int $id): StockEntity
    {
        $model = $this->getModel($dsn);

        // @phpstan-ignore-next-line
        return $model->readOrFail($id);
    }
}
