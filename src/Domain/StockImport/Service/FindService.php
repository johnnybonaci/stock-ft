<?php

namespace App\Domain\StockImport\Service;

use App\Domain\StockImport\Repository\FindRepository;
use App\DTO\FinderDTO;
use Pilot\Component\Sql\DataCollection;

/**
 * Service.
 */
final class FindService
{
    /**
     * The constructor.
     *
     * @param FindRepository $repository The repository
     */
    public function __construct(
        private FindRepository $repository,
    ) {
    }

    /**
     * Returns a collection of StockImportData objects.
     *
     * @param string $dsn Name of Pilot's instance
     * @param FinderDTO $dto search filters, sorts and paging
     *
     * @return DataCollection
     */
    public function find(string $dsn, FinderDTO $dto): DataCollection
    {
        return $this->repository->find($dsn, $dto);
    }
}
