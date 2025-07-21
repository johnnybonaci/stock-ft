<?php

namespace App\Domain\Stock\Repository;

use App\Abstract\StockRepositoryAbstract;
use App\Domain\Stock\Data\StockEntity;

/**
 * Repository.
 */
final class CreateRepository extends StockRepositoryAbstract
{
    /**
     * Create's a new Stock.
     *
     * @param  StockEntity $newStock New Stock
     * @param string $dsn
     *
     * @return StockEntity
     */
    public function create(string $dsn, StockEntity $newStock): StockEntity
    {
        $model = $this->getModel($dsn);

        // @phpstan-ignore-next-line
        return $model->create($newStock);
    }

    /**
     * Create's a new StockEntity.
     *
     * @param string $dsn
     *
     * @return StockEntity
     */
    public function createEmptyEntity(string $dsn): StockEntity
    {
        $model = $this->getModel($dsn);

        // @phpstan-ignore-next-line
        return $model->newEmptyEntity();
    }
}
