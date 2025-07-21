<?php

namespace App\Domain\Sale\Repository;

use App\Domain\Sale\Data\SaleEntity;
use App\Domain\Stock\Data\StockParameter;
use App\Exception\InvalidBusinessRulesException;
use Pilot\Component\Sql\Core\Sql;
use Psr\Container\ContainerInterface;

/**
 * Repository.
 */
final class SaleRepository
{
    private SaleModel $model;
    private Sql $connection;

    /**
     * The constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(private ContainerInterface $container)
    {
        $this->connection = $this->container->get(Sql::class);
        $this->model = new SaleModel($this->connection);
    }

    /**
     * Read's a Sales.
     *
     * @param string $guid Sales guid
     *
     * @return SaleEntity An instance of SaleEntity representing the requested Sales
     */
    public function readByGuid(string $guid): SaleEntity
    {
        $this->connection->reset();
        $this->model()->where('guid', '=', $guid);

        $sale = $this->model()->findOne();
        if (empty($sale->getId())) {
            throw new InvalidBusinessRulesException(
                sprintf("'%s' not found. Verify if the value submitted is ok", StockParameter::SALE_GUID)
            );
        }

        return $sale;
    }

    /**
     * Read's a Sales.
     *
     * @param string $guid Sales guid
     * @param int $dealerId
     *
     * @throws InvalidBusinessRulesException
     *
     * @return SaleEntity An instance of SaleEntity representing the requested Sales
     */
    public function readByGuidAndDealer(string $guid, int $dealerId): SaleEntity
    {
        $this->connection->reset();
        $this->model()->where('guid', '=', $guid)->where('dealer_id', '=', $dealerId);

        $sale = $this->model()->findOne();

        if (empty($sale->getId())) {
            throw new InvalidBusinessRulesException(
                sprintf("'%s' not found. Verify if the value submitted is ok", StockParameter::SALE_GUID)
            );
        }

        return $sale;
    }

    /**
     * model.
     *
     * @return SaleModel
     */
    private function model(): SaleModel
    {
        return $this->model;
    }
}
