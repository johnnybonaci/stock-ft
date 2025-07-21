<?php

namespace App\Domain\StockImport\Repository;

use App\Abstract\StockImportRepositoryAbstract;
use App\DTO\FinderDTO;
use Pilot\Component\Sql\DataCollection;

/**
 * Repository.
 */
final class FindRepository extends StockImportRepositoryAbstract
{
    /**
     * Find StockImport.
     *
     * @param string $dsn Name of Pilot's instance
     * @param FinderDTO $dto Params to be applied to entitie's query
     *
     * @return DataCollection Collection of StockImportEntity objects
     */
    public function find(string $dsn, FinderDTO $dto): DataCollection
    {
        $model = $this->getModel($dsn);

        foreach ($dto->filters as $filter) {
            $model->where($filter->field, $filter->operation, $filter->value);
        }

        // columnas que se retornan en la consulta
        $model->select($dto->columns);

        // uso de sorting
        foreach ($dto->sorts as $sort) {
            $model->sortBy($sort->field, $sort->order);
        }

        return $model->find($dto->page, $dto->rowsPerPage);
    }
}
