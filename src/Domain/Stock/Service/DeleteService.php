<?php

namespace App\Domain\Stock\Service;

use App\Domain\Stock\Data\StockEntity;
use App\Domain\Stock\Repository\DeleteRepository;

/**
 * Delete Service.
 */
final class DeleteService
{
    /**
     * The constructor.
     *
     * @param DeleteRepository $repository deleter repository
     */
    public function __construct(
        private DeleteRepository $repository,
    ) {
    }

    /**
     * Logical entity delete.
     *
     * @param string $dsn [Name of Pilot's instance]
     * @param int $id [identifier]
     * @param ?int $auditUserId [user to audit]
     *
     * @return StockEntity
     */
    public function softDelete(string $dsn, int $id, ?int $auditUserId = null): StockEntity
    {
        return $this->repository->softDelete($dsn, $id, $auditUserId);
    }

    /**
     * Hard delete.
     *
     * @param string $dsn Name of Pilot's instance
     * @param int $id Id of the Stock to be deleted
     *
     * @return array
     */
    public function delete(string $dsn, int $id): array
    {
        return $this->repository->delete($dsn, $id);
    }
}
