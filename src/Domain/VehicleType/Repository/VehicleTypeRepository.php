<?php

namespace App\Domain\VehicleType\Repository;

use App\Abstract\RepositoryAbstract;
use App\Domain\Stock\Data\StockParameter;
use App\Domain\VehicleType\Data\VehicleTypeCollection;
use App\Domain\VehicleType\Data\VehicleTypeData;
use App\Exception\MicroservicesClientException;
use App\Exception\NotFoundException;
use Pilot\Component\MicroserviceClient\Abstracts\MicroserviceClientAbstract;
use Pilot\Component\MicroserviceClient\Entities\HttpResultEntity;
use Pilot\Component\MicroserviceClient\MicroserviceClient;
use Psr\Container\ContainerInterface;

/**
 * Repository.
 */
final class VehicleTypeRepository extends RepositoryAbstract
{
    private const MS_NAME = 'hub-lookup-stock-vehicles-types';
    private const PATH = 'vehicles-types';
    private const TABLE_NAME = 'hub_lk_stock_vehicles_types';

    /**
     * The constructor.
     *
     * @param MicroserviceClient $microservicesClient Clase para llamar a los microservicios del backend
     * @param ContainerInterface $container Clase para pasar al padre
     */
    public function __construct(
        private MicroserviceClient $microservicesClient,
        ContainerInterface $container,
    ) {
        parent::__construct($container);
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
     * @return VehicleTypeCollection Collection of VehicleTypeData objects
     */
    public function find(
        string $dsn,
        int $page = 1,
        int $rowsPerPage = 25,
        array $filters = [],
        array $sorts = [],
    ): VehicleTypeCollection {
        $uri = sprintf('%s/%s', $dsn, self::PATH);

        $params = [
            'sorts' => $sorts,
            'filters' => $filters,
            'page' => $page,
            'rows_per_page' => $rowsPerPage,
        ];

        $httpResult = $this->microserviceClient()->get($uri, $params);

        $this->checkSuccess($httpResult);

        return (new VehicleTypeCollection())->hydrate($httpResult->getData() ?? [], VehicleTypeData::class);
    }

    /**
     * This PHP function retrieves a row of data from a database based on a given code.
     *
     * @param string $dsn
     * @param string $code
     *
     * @throws MicroservicesClientException
     *
     * @return VehicleTypeData The `getRowByCode` function is returning a single `VehicleTypeData` object that
     * matches the provided code from the data source specified by the DSN. If no matching record is
     * found, it throws a `MicroservicesClientException` with the error.
     */
    public function getRowByCode(string $dsn, string $code): VehicleTypeData
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
                sprintf("'%s' not found. Verify if the value submitted is ok", StockParameter::VEHICLE_TYPE_CODE)
            );
        }

        return $resp->getFirst();
    }

    /**
     * This PHP function retrieves a row of data from a database based on a given code.
     *
     * @param string $code
     *
     * @throws NotFoundException
     *
     * @return VehicleTypeData The `readByCode` function is returning a single `VehicleTypeData` object that
     * matches the provided code from the data source. If no matching record is
     * found, it throws a `NotFoundException` with the error.
     */
    public function readByCode(string $code): VehicleTypeData
    {
        $this->connection->reset();
        $this->connection->newLine("SELECT * FROM @table. WHERE code = '@code.';");

        $this->connection->bind('table', self::TABLE_NAME);
        $this->connection->bind('code', (string)$code);
        $result = $this->connection->getArray();

        $resp = $result[0] ?? [];
        if (empty($resp)) {
            throw new NotFoundException(
                sprintf("'%s' not found. Verify if the value submitted is ok.", StockParameter::VEHICLE_TYPE_CODE)
            );
        }

        return (new VehicleTypeData())->loadFromState($resp);
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
