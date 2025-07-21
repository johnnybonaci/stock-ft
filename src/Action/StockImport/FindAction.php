<?php

namespace App\Action\StockImport;

use App\Abstract\StockImportActionAbstract;
use App\Domain\StockImport\Service\FindService;
use App\DTO\FinderDTO;
use Pilot\Component\Renderers\StandardResponse;
use Pilot\Component\RequestParameter\Factories\RequestParameterFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * FindAction.
 */
final class FindAction extends StockImportActionAbstract
{
    /**
     * The constructor.
     *
     * @param ContainerInterface $container The container, for dependency injection
     * @param FindService $service Service designed to create a given entity
     * @param StandardResponse $standardOutput Standard response
     */
    public function __construct(
        private ContainerInterface $container, // @phpstan-ignore-line
        private FindService $service,
        private StandardResponse $standardOutput,
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
            $requestParameters = RequestParameterFactory::create($request);
            $dsn = $request->getAttribute('dsn');

            $criteria = new FinderDTO(
                $requestParameters->get('page')->default(1)->getValue(),
                $requestParameters->get('rows_per_page')->default(25)->getValue(),
                $requestParameters->get('columns')->default([])->getValue(),
                $requestParameters->get('filters')->default([])->getValue(),
                $requestParameters->get('sorts')->default([])->getValue()
            );

            $stockImport = $this->service->find($dsn, $criteria);

            $return = $this
                ->standardOutput
                ->setOutput(true, 'StockImport find OK', $stockImport->toArray());
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
}
