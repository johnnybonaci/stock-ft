<?php

namespace App\Domain\Stock\Repository;

use App\Abstract\StockRepositoryAbstract;
use App\Domain\Stock\Data\StockEntity;

/**
 * Repository.
 */
final class DeleteRepository extends StockRepositoryAbstract
{
    /**
     * Request for the deletion of the indicated Stock
     * by a given id.
     *
     * @param string $dsn Name of instance
     * @param int $id Stock id
     *
     * @return array
     */
    public function delete(string $dsn, int $id): array
    {
        $model = $this->getModel($dsn);

        return $model->delete($id);
    }

    /**
     * Request for the soft-deletion of the indicated Stock
     * by a given id.
     *
     * @param string $dsn Name of instance
     * @param int $id Stock id
     * @param ?int $auditUser
     *
     * @return StockEntity an instance of StockEntity representing the deleted Stock
     */
    public function softDelete(string $dsn, int $id, ?int $auditUser = null): StockEntity
    {
        $model = $this->getModel($dsn);

        $entity = $model->read($id);

        $entity->setDeleted(1);
        $entity->setAudit_deleted_user_id($auditUser);
        $entity->setAudit_deleted_dt((new \DateTime())->format('Y-m-d H:i:s'));

        /* @phpstan-ignore-next-line */
        return $model->update($entity);
    }
}
