<?php

namespace App\Domain\ProductVehicle\Repository;

use App\Domain\ProductVehicle\Data\ProductVehicleCollection;
use App\Domain\ProductVehicle\Data\ProductVehicleData;
use App\Domain\Stock\Data\StockParameter;
use App\Exception\MicroservicesClientException;
use Pilot\Component\MicroserviceClient\Abstracts\MicroserviceClientAbstract;
use Pilot\Component\MicroserviceClient\Entities\HttpResultEntity;
use Pilot\Component\MicroserviceClient\MicroserviceClient;

/**
 * Repository.
 */
final class ProductVehicleRepository
{
    private const MS_NAME = 'hub-lookup-product-vehicles';
    private const PATH = 'product-vehicles';

    /**
     * The constructor.
     *
     * @param MicroserviceClient $microservicesClient Clase para llamar a los microservicios del backend
     */
    public function __construct(private MicroserviceClient $microservicesClient)
    {
    }

    /**
     * Finds entity, given certain filters.
     *
     * @param string $dsn Name of Pilot's instance
     * @param array $filters Filters to be applied to entitie's query
     * @param int $page
     * @param int $rowsPerPage
     * @param array $sorts
     *
     * @return ProductVehicleCollection Collection of ProductVehicleData objects
     */
    public function find(
        string $dsn,
        int $page = 1,
        int $rowsPerPage = 25,
        array $filters = [],
        array $sorts = [],
    ): ProductVehicleCollection {
        $uri = sprintf('%s/%s', $dsn, self::PATH);

        $params = [
            'sorts' => $sorts,
            'filters' => $filters,
            'page' => $page,
            'rows_per_page' => $rowsPerPage,
        ];

        $httpResult = $this->microserviceClient()->get($uri, $params);

        $this->checkSuccess($httpResult);

        return (new ProductVehicleCollection())->hydrate($httpResult->getData() ?? [], ProductVehicleData::class);
    }

    /**
     * This PHP function retrieves a row of data from a database based on a given code.
     *
     * @param string $dsn
     * @param string $code
     *
     * @throws MicroservicesClientException
     *
     * @return ProductVehicleData The `getRowByCode` function is returning a single `ProductVehicleData` object that
     * matches the provided code from the data source specified by the DSN. If no matching record is
     * found, it throws a `MicroservicesClientException` with the error.
     */
    public function getRowByCode(string $dsn, string $code): ProductVehicleData
    {
        $filters = [
            [
                'field' => 'code',
                'operation' => '=',
                'value' => $code,
            ],
        ];

        $resp = $this->find($dsn, 1, 1, $filters);
        if ($resp->count() == 0) {
            throw new MicroservicesClientException(
                sprintf("'%s' not found. Verify if the value submitted is ok", StockParameter::PRODUCT_VEHICLE_CODE)
            );
        }

        return $resp->getFirst();
    }

    /**
     * Checks wheter the microservice returned a success message
     * or not.
     *
     * @param  HttpResultEntity $httpResultEntity Result from calling a microservice
     *
     * @throws MicroservicesClientException
     *
     * @return void
     */
    private function checkSuccess(HttpResultEntity $httpResultEntity)
    {
        if (!$httpResultEntity->isSuccess()) {
            throw new MicroservicesClientException($httpResultEntity->getMessage());
        }
    }

    /**
     * microserviceClient.
     *
     * @return MicroserviceClientAbstract
     */
    private function microserviceClient(): MicroserviceClientAbstract
    {
        return $this->microservicesClient->setMicroservice(self::MS_NAME);
    }
}
