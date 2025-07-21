<?php

namespace App\Domain\StockStatus\Repository;

use App\Abstract\RepositoryAbstract;
use App\Domain\Stock\Data\StockParameter;
use App\Domain\StockStatus\Data\StockStatusCollection;
use App\Domain\StockStatus\Data\StockStatusData;
use App\Exception\MicroservicesClientException;
use App\Exception\NotFoundException;
use Pilot\Component\MicroserviceClient\Abstracts\MicroserviceClientAbstract;
use Pilot\Component\MicroserviceClient\Entities\HttpResultEntity;
use Pilot\Component\MicroserviceClient\MicroserviceClient;
use Psr\Container\ContainerInterface;

/**
 * Repository.
 */
final class StockStatusRepository extends RepositoryAbstract
{
    private const MS_NAME = 'hub-lookup-stock-status';
    private const PATH = 'status';
    private const TABLE_NAME = 'hub_lk_stock_status';

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
     * @return StockStatusCollection Collection of StockStatusData objects
     */
    public function find(
        string $dsn,
        int $page = 1,
        int $rowsPerPage = 25,
        array $filters = [],
        array $sorts = [],
    ): StockStatusCollection {
        $uri = sprintf('%s/%s', $dsn, self::PATH);

        $params = [
            'sorts' => $sorts,
            'filters' => $filters,
            'page' => $page,
            'rows_per_page' => $rowsPerPage,
        ];

        $httpResult = $this->microserviceClient()->get($uri, $params);

        $this->checkSuccess($httpResult);

        return (new StockStatusCollection())->hydrate($httpResult->getData() ?? [], StockStatusData::class);
    }

    /**
     * This PHP function retrieves a row of data from a database based on a given code.
     *
     * @param string $dsn
     * @param string $code
     *
     * @throws MicroservicesClientException
     *
     * @return StockStatusData The `getRowByCode` function is returning a single `StockStatusData` object that
     * matches the provided code from the data source specified by the DSN. If no matching record is
     * found, it throws a `MicroservicesClientException` with the error.
     */
    public function getRowByCode(string $dsn, string $code): StockStatusData
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
                sprintf("'%s' not found. Verify if the value submitted is ok", StockParameter::STATUS_CODE)
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
     * @return StockStatusData The `readByCode` function is returning a single `StockStatusData` object that
     * matches the provided code from the data source. If no matching record is
     * found, it throws a `NotFoundException` with the error.
     */
    public function readByCode(string $code): StockStatusData
    {
        $this->connection->reset();
        $this->connection->newLine("SELECT * FROM @table. WHERE code = '@code.';");

        $this->connection->bind('table', self::TABLE_NAME);
        $this->connection->bind('code', (string)$code);
        $result = $this->connection->getArray();

        $resp = $result[0] ?? [];
        if (empty($resp)) {
            throw new NotFoundException(
                sprintf("'%s' not found. Verify if the value submitted is ok.", StockParameter::STATUS_CODE)
            );
        }

        return (new StockStatusData())->loadFromState($resp);
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
