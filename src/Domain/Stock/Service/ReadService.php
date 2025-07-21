<?php

namespace App\Domain\Stock\Service;

use App\Domain\Stock\Data\StockEntity;
use App\Domain\Stock\Repository\ReadRepository;

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
     * Returns the Stock as a StockEntity object.
     *
     * @param string $dsn data source name (~instance)
     * @param int $id Currencie's Id
     *
     * @return StockEntity
     */
    public function read(string $dsn, int $id): StockEntity
    {
        return $this->repository->read($dsn, $id);
    }
}
