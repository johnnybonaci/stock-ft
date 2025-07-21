<?php

namespace App\Abstract;

use App\Abstract\StockActionAbstract;
use App\Domain\Stock\Service\BackendSecurityCommandService;
use App\Domain\Stock\Service\FindService;
use App\DTO\FinderDTO;
use Pilot\Component\Renderers\StandardResponse;
use Pilot\Component\RequestParameter\Factories\RequestParameterFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Abstract base class for Find actions using Template Method pattern
 */
abstract class FindActionAbstract extends StockActionAbstract
{
    public function __construct(
        private ContainerInterface $container,
        private FindService $service,
        private StandardResponse $standardOutput,
        private BackendSecurityCommandService $backendCommandService,
    ) {
        parent::__construct($container);
    }


    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $requestParameters = RequestParameterFactory::create($request);
            $dsn = $this->resolveDsn($request);


            $criteria = new FinderDTO(
                $requestParameters->get('page')->default(1)->getValue(),
                $requestParameters->get('rows_per_page')->default(25)->getValue(),
                $requestParameters->get('columns')->default([])->getValue(),
                $requestParameters->get('filters')->default([])->getValue(),
                $requestParameters->get('sorts')->default([])->getValue()
            );

            $stock = $this->service->find($dsn, $criteria);

            $return = $this
                ->standardOutput
                ->setOutput(true, 'Stock find OK', $stock->toArray());
        } catch (\Pilot\Component\RequestParameter\Exceptions\RequiredParameterException $e) {
            $return = $this->standardOutput
                ->setOutput(
                    false,
                    $e->getMessage(),
                    [],
                    400,
                    $e->getSubcode()
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
        } catch (\Throwable $th) {
            $return = $this->standardOutput
                ->setOutput(
                    false,
                    $th->getMessage(),
                    [
                        'file' => $th->getFile(),
                        'line' => $th->getLine(),
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