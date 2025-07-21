<?php

namespace App\Domain\Stock\Repository;

use App\Abstract\StockRepositoryAbstract;
use App\Domain\Stock\Data\StockEntity;

/**
 * Repository.
 */
final class UpdateRepository extends StockRepositoryAbstract
{
    /**
     * Update's an existing Stock.
     *
     * @param string $dsn data source name (~instance)
     * @param  StockEntity $entityData Updated entity Stock
     *
     * @return StockEntity
     */
    public function update(string $dsn, StockEntity $entityData): StockEntity
    {
        $model = $this->getModel($dsn);

        $entityData->setAudit_updated_dt((new \DateTime())->format('Y-m-d H:i:s'));

        // @phpstan-ignore-next-line
        return $model->update($entityData);
    }
}
