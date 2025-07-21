<?php

namespace App\Action\Stock;

use App\Abstract\StockActionAbstract;
use App\Domain\Stock\Data\StockBusinessRulesValidator;
use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockParameter;
use App\Domain\Stock\Service\BackendSecurityCommandService;
use App\Domain\Stock\Service\CreateService;
use App\Domain\Stock\Service\FindService;
use App\Domain\Stock\Service\UpdateService;
use DI\Container;
use Pilot\Component\Renderers\StandardResponse;
use Pilot\Component\RequestParameter\Factories\RequestParameterFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * UpsertAction.
 */
final class UpsertAction extends StockActionAbstract
{
    /**
     * The constructor.
     *
     * @param Container $container The container, for dependency injection
     * @param CreateService $createService: Service designed to create a given entity
     * @param UpdateService $updateService: Service designed to update a given entity
     * @param FindService $findService: Service designed to find a given entity
     * @param StandardResponse $standardOutput Standard response
     * @param BackendSecurityCommandService $backendCommandService commands permissions
     * @param StockBusinessRulesValidator $stockBusinessRulesValidator
     */
    public function __construct(
        private Container $container,
        private CreateService $createService,
        private UpdateService $updateService,
        private FindService $findService,
        private StandardResponse $standardOutput,
        private BackendSecurityCommandService $backendCommandService,
        private StockBusinessRulesValidator $stockBusinessRulesValidator,
    ) {
        parent::__construct($container);
    }

    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     * @param array $args Arguments
     *
     * @return ResponseInterface The response
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $dsn = $request->getAttribute('dsn');
            $this->container->set('dsn', $dsn);
            $requestParameters = RequestParameterFactory::create($request);

            $auditUserId = $requestParameters->get(StockParameter::AUDIT_USER_ID)
                ->required(sprintf("Field '%s' is required", StockParameter::AUDIT_USER_ID), false)
                ->getValue();
            $vin = $requestParameters->get(StockParameter::VEHICLE_IDENTIFICATION_NUMBER)
                ->required(sprintf("Field '%s' is required", StockParameter::VEHICLE_IDENTIFICATION_NUMBER))
                ->getValue();
            
            $stockData = new StockData();
            

            $result = $this->findService->findByVin($dsn, $vin);
            if ($result->getTotalRows() > 0) {
                $entity = $result->getFirst();
                $stockData->loadFromState($entity->toArray());
                $stockData->setId($entity->getId());

                $stockData->setAuditUpdatedUserId($auditUserId);
            }else{                
                $stockData->setAuditCreatedUserId($auditUserId);
            }

            $stockData = $this->loadDataFromParamsOrFail($stockData, $requestParameters);
            // Validacion de reglas de negocio
            $stockData = $this->stockBusinessRulesValidator->createValidate($stockData);

            if ($stockData->getId()) {
                $stockEntity = $this->updateService->update($dsn, $stockData->getId(), $stockData);
            }else{
                $stockEntity = $this->createService->create($dsn, $stockData);
            }

            $return = $this->standardOutput->setOutput(
                true,
                'Stock upsert OK',
                $stockEntity->toArray(),
                200,
                'stock_upsert_ok'
            );
        } catch (\App\Exception\SecurityCommandException $e) {
            $return = $this->standardOutput
                ->setOutput(
                    false,
                    $e->getMessage(),
                    $e->getContext(),
                    $e->getCode(),
                    $e->getSubcode()
                );
        } catch (
            \App\Exception\MicroservicesClientException
            |\Pilot\Component\MicroserviceClient\Exceptions\MicroservicesHttpClientException $e
        ) {
            $return = $this->standardOutput
                ->setOutput(
                    false,
                    $e->getMessage(),
                    $e->getContext(),
                    $e->getCode(),
                    $e->getSubcode()
                );
        } catch (\App\Exception\NotFoundException $e) {
            $return = $this->standardOutput
                ->setOutput(
                    false,
                    $e->getMessage(),
                    $e->getContext(),
                    $e->getCode(),
                    $e->getSubcode()
                );
        } catch (\App\Exception\InvalidBusinessRulesException $e) {
            $return = $this->standardOutput
                ->setOutput(
                    false,
                    $e->getMessage(),
                    $e->getContext(),
                    $e->getCode(),
                    $e->getSubcode()
                );
        } catch (\Pilot\Component\RequestParameter\Exceptions\RequiredParameterException $e) {
            $return = $this->standardOutput
                ->setOutput(
                    false,
                    $e->getMessage(),
                    [],
                    400,
                    $e->getSubcode()
                );
        } catch (\Throwable $th) {
            $return = $this->standardOutput->setOutput(
                false,
                $th->getMessage(),
                [
                    'file' => $th->getFile(),
                    'line' => $th->getLine(),
                    'params' => $request->getParsedBody(),
                ],
                $th->getCode(),
                'unhandled_error'
            );
        }

        return $return->response($response);
    }
}
