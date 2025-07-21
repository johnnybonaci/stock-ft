<?php

namespace App\Domain\Stock\Repository;

use App\Abstract\StockRepositoryAbstract;
use App\DTO\FinderDTO;
use Pilot\Component\Sql\DataCollection;

/**
 * Repository.
 */
final class FindRepository extends StockRepositoryAbstract
{
    /**
     * Find Stock.
     *
     * @param string $dsn Name of Pilot's instance
     * @param FinderDTO $dto Params to be applied to entitie's query
     *
     * @return DataCollection Collection of StockEntity objects
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
