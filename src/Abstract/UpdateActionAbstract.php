<?php

namespace App\Abstract;

use App\Abstract\StockActionAbstract;
use App\Domain\Stock\Data\StockBusinessRulesValidator;
use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockParameter;
use App\Domain\Stock\Service\BackendSecurityCommandService;
use App\Domain\Stock\Service\UpdateService;
use DI\Container;
use Pilot\Component\Renderers\StandardResponse;
use Pilot\Component\RequestParameter\Factories\RequestParameterFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * UpdateAction.
 */
abstract class UpdateActionAbstract extends StockActionAbstract
{
    public function __construct(
        private Container $container,
        private UpdateService $service,
        private StandardResponse $standardOutput,
        private BackendSecurityCommandService $backendCommandService,
        private StockBusinessRulesValidator $stockBusinessRulesValidator,
    ) {
        parent::__construct($container);
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $id = $request->getAttribute('id');
            $dsn = $this->resolveDsn($request);
            $this->container->set('dsn', $dsn);
            $requestParameters = RequestParameterFactory::create($request);

            // descomentar si se necesita updated_user_id, o borrar si no se utiliza
            $auditUserId = $requestParameters->get(StockParameter::AUDIT_USER_ID)
                ->required(sprintf("Field '%s' is required", StockParameter::AUDIT_USER_ID), false)
                ->getValue();

            $stockData = new StockData();
            $stockData = $this->loadDataFromParamsOrFail($stockData, $requestParameters);

            $stockData->setId($id);
            $stockData->setAuditUpdatedUserId($auditUserId);

            // Validacion de reglas de negocio
            $stockData = $this->stockBusinessRulesValidator->createValidate($stockData);

            $stockEntity = $this->service->update($dsn, $id, $stockData);

            $return = $this->standardOutput->setOutput(
                true,
                'Stock updated OK',
                $stockEntity->toArray(),
                200,
                'stock_updated_ok'
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
        } catch (\Pilot\Component\Sql\Exceptions\RecordNotFoundException $e) {
            $return = $this->standardOutput
                ->setOutput(
                    false,
                    $e->getMessage(),
                    [],
                    404,
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
    
    /**
     * DSN resolution
     */
    abstract protected function resolveDsn(ServerRequestInterface $request): string;
}
