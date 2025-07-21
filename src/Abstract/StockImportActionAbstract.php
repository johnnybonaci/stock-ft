<?php

namespace App\Abstract;

use App\Domain\StockImport\Data\StockImportEntity;
use App\Domain\StockImport\Data\StockImportParameter;
use App\Domain\StockImport\Data\StockImportStatusConstants;
use App\Domain\StockImport\Repository\StockImportModel;
use Pilot\Component\Abstracts\AbstractAction;
use Pilot\Component\RequestParameter\RequestParameter;
use Pilot\Component\Sql\Core\Sql;
use Psr\Container\ContainerInterface;

/**
 * Action.
 */
abstract class StockImportActionAbstract extends AbstractAction
{
    /**
     * The constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(private ContainerInterface $container)
    {
    }

    protected function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Obtiene los parametros y verifica cuales son requeridos para poder crear la entidad.
     *
     * @param string $dsn
     * @param RequestParameter $requestParameters
     *
     * @return StockImportEntity
     */
    public function loadEntityFromParamsOrFail(string $dsn, RequestParameter $requestParameters): StockImportEntity
    {
        $connection = $this->container->get(Sql::class)->open($dsn, 'hub');
        $model = new StockImportModel($connection);
        $stockImportEntity = $model->newEmptyEntity();

        $stockImportEntity->setInput_file_s3_key(
            strtolower(trim($requestParameters->get(StockImportParameter::INPUT_FILE_S3_KEY)
            ->required("Field 'input_file_s3_key' is required")
            ->getValue()))
        );
        $stockImportEntity->setRequest_user_id(
            $requestParameters->get(StockImportParameter::REQUEST_USER_ID)
            ->required("Field 'request_user_id' is required")
            ->getValue()
        );
        $stockImportEntity->setRequest_user_name(
            trim($requestParameters->get(StockImportParameter::REQUEST_USER_NAME)
            ->required("Field 'request_user_name' is required")
            ->getValue())
        );
        $stockImportEntity->setRequest_dt(
            $requestParameters->get(StockImportParameter::REQUEST_DT)
            ->default(date('Y-m-d H:i:s'))
            ->getValue()
        );
        $stockImportEntity->setRequest_status_code(StockImportStatusConstants::PENDING);
        $stockImportEntity->setImport_records_qty(null);
        $stockImportEntity->setOutput_log_file_s3_key(null);
        $stockImportEntity->setImport_end_dt(null);

        return $stockImportEntity;
    }
}
