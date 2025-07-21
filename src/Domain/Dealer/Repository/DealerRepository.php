<?php

namespace App\Domain\Dealer\Repository;

use App\Domain\Dealer\Data\DealerCollection;
use App\Domain\Dealer\Data\DealerData;
use App\Domain\Stock\Data\StockParameter;
use App\Exception\MicroservicesClientException;
use Pilot\Component\MicroserviceClient\Abstracts\MicroserviceClientAbstract;
use Pilot\Component\MicroserviceClient\Entities\HttpResultEntity;
use Pilot\Component\MicroserviceClient\MicroserviceClient;

/**
 * Repository.
 */
final class DealerRepository
{
    private const MS_NAME = 'hub-lookup-dealers';
    private const PATH = 'dealers';

    /**
     * The constructor.
     *
     * @param MicroserviceClient $microservicesClient Clase para llamar a los microservicios del backend
     */
    public function __construct(private MicroserviceClient $microservicesClient)
    {
    }

    /**
     * The `find` function retrieves a collection of Dealer data based on specified parameters
     * from a microservice using HTTP GET request.
     *
     * @param string $dsn Name of Pilot's instance
     * @param int $page
     * @param int $rowsPerPage
     * @param array $filters Filters to be applied to entitie's query
     * @param array $sorts
     *
     * @return DealerCollection the `find` method returns a `DealerCollection` object after
     * fetching data from a microservice endpoint, processing it, and hydrating it into a collection of
     * `DealerData` objects
     */
    public function find(
        string $dsn,
        int $page = 1,
        int $rowsPerPage = 25,
        array $filters = [],
        array $sorts = [],
    ): DealerCollection {
        $uri = sprintf('%s/%s', $dsn, self::PATH);

        $params = [
            'sorts' => $sorts,
            'filters' => $filters,
            'page' => $page,
            'rows_per_page' => $rowsPerPage,
        ];

        $httpResult = $this->microserviceClient()->get($uri, $params);

        $this->checkSuccess($httpResult);

        return (new DealerCollection())->hydrate($httpResult->getData() ?? [], DealerData::class);
    }

    /**
     * This PHP function retrieves a row of data from a database based on a given code.
     *
     * @param string $dsn
     * @param string $code
     *
     * @throws MicroservicesClientException
     *
     * @return DealerData The `getRowByCode` function is returning a single `DealerData` object that
     * matches the provided code from the data source specified by the DSN. If no matching record is
     * found, it throws a `MicroservicesClientException` with the error code.
     */
    public function getRowByCode(string $dsn, string $code): DealerData
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
                sprintf("'%s' not found. Verify if the value submitted is ok", StockParameter::DEALER_CODE)
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
