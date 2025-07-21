<?php

namespace App\Services;

use App\Exception\QueueManagerServiceException;
use Pilot\Component\MicroserviceClient\Abstracts\MicroserviceClientAbstract;
use Pilot\Component\MicroserviceClient\Entities\HttpResultEntity;
use Pilot\Component\MicroserviceClient\MicroserviceClient;
use Psr\Container\ContainerInterface;

class QueueManagerService
{
    private const MS_NAME = 'iaas-queue-manager';
    private const QUEUE_NAME = 'IAAS_QUEUE_MANAGER_HUB_FACTORY_STOCK_IMPORT_FILE_URI';

    public function __construct(
        private ContainerInterface $container,
        private MicroserviceClient $microservicesClient,
    ) {}

    /**
     * Función para encolar evento en SQS.
     *
     * @param array $payload
     *
     * @return ?array
     */
    public function enqueue(array $payload): ?array
    {
        $uri = sprintf('%s/messages', self::QUEUE_NAME);
        $response = $this->microserviceClient()->post(
            $uri,
            $payload
        );

        return $this->parseResponse($response, $payload);
    }

    /**
     * Funció para parsear la respuesta de la api.
     *
     * @param HttpResultEntity $response
     * @param array $payload
     *
     * @throws QueueManagerServiceException
     *
     * @return ?array
     */
    protected function parseResponse(HttpResultEntity $response, array $payload = []): ?array
    {
        if (!$response->isSuccess()) {
            $message = json_encode(['ms_message' => $response->getMessage(), 'payload' => $payload]);

            throw new QueueManagerServiceException(
                (string)$message,
                $payload,
                $response->getCode(),
                $response->getSubCode()
            );
        }

        return $response->getData();
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
