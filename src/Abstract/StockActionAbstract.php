<?php

namespace App\Abstract;

use App\Constant\DataTypeConstants;
use App\Constant\ValidationConstants;
use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockEntity;
use App\Domain\Stock\Data\StockParameter;
use App\Domain\Stock\Repository\StockModel;
use Pilot\Component\Abstracts\AbstractAction;
use Pilot\Component\RequestParameter\RequestParameter;
use Pilot\Component\Sql\Core\Sql;
use Psr\Container\ContainerInterface;

/**
 * Action.
 */
abstract class StockActionAbstract extends AbstractAction
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
     * @param StockData $stockData
     * @param RequestParameter $requestParameters
     *
     * @return StockData
     */
    public function loadDataFromParamsOrFail(StockData $stockData, RequestParameter $requestParameters): StockData
    {
        $stockData->setProductVehicleCode($requestParameters->get(StockParameter::PRODUCT_VEHICLE_CODE)
            ->required(sprintf("Field '%s' is required", StockParameter::PRODUCT_VEHICLE_CODE))
            ->assertRegex(DataTypeConstants::VALID_LENGTH_50_PATTERN, sprintf("'%s' field not valid. Must be 50 characters max", StockParameter::PRODUCT_VEHICLE_CODE))
            ->assertBusinessCode(sprintf("'%s' field not valid. Must contain letters (a-z), digits (0-9) and -_", StockParameter::PRODUCT_VEHICLE_CODE))
            ->getValue());
        $stockData->setVehicleTypeCode($requestParameters->get(StockParameter::VEHICLE_TYPE_CODE)
            ->required(sprintf("Field '%s' is required", StockParameter::VEHICLE_TYPE_CODE))
            ->assertRegex(DataTypeConstants::VALID_LENGTH_50_PATTERN, sprintf("'%s' field not valid. Must be 50 characters max", StockParameter::VEHICLE_TYPE_CODE))
            ->assertBusinessCode(sprintf("'%s' field not valid. Must contain letters (a-z), digits (0-9) and -_", StockParameter::VEHICLE_TYPE_CODE))
            ->getValue());
        $stockData->setVehicleIdentificationNumber($requestParameters->get(StockParameter::VEHICLE_IDENTIFICATION_NUMBER)
            ->required(sprintf("Field '%s' is required", StockParameter::VEHICLE_IDENTIFICATION_NUMBER))
            ->assertRegex(DataTypeConstants::VALID_LENGTH_20_PATTERN, sprintf("'%s' field not valid. Must be 20 characters max", StockParameter::VEHICLE_IDENTIFICATION_NUMBER))
            ->assertAlphanumeric(sprintf("'%s' field not valid. Must contain letters (a-z) and digits (0-9)", StockParameter::VEHICLE_IDENTIFICATION_NUMBER))
            ->getValue());
        $stockData->setChassisNumber($requestParameters->get(StockParameter::CHASSIS_NUMBER)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_100_PATTERN, sprintf("'%s' field not valid. Must be 100 characters max", StockParameter::CHASSIS_NUMBER))
            ->assertRegex(DataTypeConstants::VALID_CHASSIS_NUMBER_PATTERN, sprintf("'%s' field not valid. Must contain letters (a-z), digits (0-9) and -", StockParameter::CHASSIS_NUMBER))
            ->default(null)
            ->getValue());
        $stockData->setEngineNumber($requestParameters->get(StockParameter::ENGINE_NUMBER)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_100_PATTERN, sprintf("'%s' field not valid. Must be 100 characters max", StockParameter::ENGINE_NUMBER))
            ->assertRegex(DataTypeConstants::VALID_ENGINE_NUMBER_PATTERN, sprintf("'%s' field not valid. Must contain letters (a-z), digits (0-9), -_ and /", StockParameter::ENGINE_NUMBER))
            ->default(null)
            ->getValue());
        $stockData->setFactoryOrderNumber($requestParameters->get(StockParameter::FACTORY_ORDER_NUMBER)
            ->required(sprintf("Field '%s' is required", StockParameter::FACTORY_ORDER_NUMBER))
            ->assertRegex(DataTypeConstants::VALID_LENGTH_100_PATTERN, sprintf("'%s' field not valid. Must be 100 characters max", StockParameter::FACTORY_ORDER_NUMBER))
            ->default(null)
            ->getValue());
        $stockData->setDealerInvoiceDate($requestParameters->get(StockParameter::DEALER_INVOICE_DATE)
            ->assertRegex(DataTypeConstants::VALID_DATE_PATTERN, sprintf("'%s' field not valid. Must be a valid date format (YYYY-MM-DD)", StockParameter::DEALER_INVOICE_DATE))
            ->default(null)
            ->getValue());
        $stockData->setDealerInvoiceNumber($requestParameters->get(StockParameter::DEALER_INVOICE_NUMBER)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_50_PATTERN, sprintf("'%s' field not valid. Must be 50 characters max", StockParameter::DEALER_INVOICE_NUMBER))
            ->assertRegex(DataTypeConstants::VALID_INVOICE_NUMBER_PATTERN, sprintf("'%s' field not valid. Must contain letters (a-z), digits (0-9) and -", StockParameter::DEALER_INVOICE_NUMBER))
            ->default(null)
            ->getValue());
        $stockData->setSalesChannel($requestParameters->get(StockParameter::SALES_CHANNEL)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_50_PATTERN, sprintf("'%s' field not valid. Must be 50 characters max", StockParameter::SALES_CHANNEL))
            ->default(null)
            ->getValue());
        $stockData->setColor($requestParameters->get(StockParameter::COLOR)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_50_PATTERN, sprintf("'%s' field not valid. Must be 50 characters max", StockParameter::COLOR))
            ->default(null)
            ->getValue());
        $stockData->setFactoryState($requestParameters->get(StockParameter::FACTORY_STATE)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_50_PATTERN, sprintf("'%s' field not valid. Must be 50 characters max", StockParameter::FACTORY_STATE))
            ->default(null)
            ->getValue());
        $stockData->setDealerCode($requestParameters->get(StockParameter::DEALER_CODE)
            ->required(sprintf("Field '%s' is required", StockParameter::DEALER_CODE))
            ->assertRegex(DataTypeConstants::VALID_LENGTH_50_PATTERN, sprintf("'%s' field not valid. Must be 50 characters max", StockParameter::DEALER_CODE))
            ->getValue());

        $stockData->setStatusCode($requestParameters->get(StockParameter::STATUS_CODE)
            ->required(sprintf("Field '%s' is required", StockParameter::STATUS_CODE))
            ->assertRegex(DataTypeConstants::VALID_LENGTH_50_PATTERN, sprintf("'%s' field not valid. Must be 50 characters max", StockParameter::STATUS_CODE))
            ->assertBusinessCode(sprintf("'%s' field not valid. Must contain letters (a-z), digits (0-9) and -_", StockParameter::STATUS_CODE))
            ->getValue());

        $stockData->setSaleId($requestParameters->get(StockParameter::SALE_GUID)
            ->assertRegex(DataTypeConstants::VALID_GUID_PATTERN, sprintf("'%s' (GUID) format not valid", StockParameter::SALE_GUID))
            ->default(null)
            ->getValue());

        $stockData->setSoldDate($requestParameters->get(StockParameter::SOLD_DATE)
            ->assertRegex(DataTypeConstants::VALID_DATE_PATTERN, sprintf("'%s' field not valid. Must be a valid date format (YYYY-MM-DD)", StockParameter::SOLD_DATE))
            ->default(null)
            ->getValue());

        $stockData->setWarrantyStartDate($requestParameters->get(StockParameter::WARRANTY_START_DATE)
            ->assertRegex(DataTypeConstants::VALID_DATE_WITH_EMPTY_PATTERN, sprintf("'%s' field not valid. Must be a valid date format (YYYY-MM-DD)", StockParameter::WARRANTY_START_DATE))
            ->default(null)
            ->getValue());
        $stockData->setWarrantyEndDate($requestParameters->get(StockParameter::WARRANTY_END_DATE)
            ->assertRegex(DataTypeConstants::VALID_DATE_WITH_EMPTY_PATTERN, sprintf("'%s' field not valid. Must be a valid date format (YYYY-MM-DD)", StockParameter::WARRANTY_END_DATE))
            ->default(null)
            ->getValue());
        $stockData->setWarrantyNotes($requestParameters->get(StockParameter::WARRANTY_NOTES)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_500_PATTERN, sprintf("'%s' field not valid. Must be 500 characters max", StockParameter::WARRANTY_NOTES))
            ->default(null)
            ->getValue());
        $stockData->setWarrantyKms($requestParameters->get(StockParameter::WARRANTY_KMS)
            ->assertNumeric(
                sprintf("'%s' field not valid. Must contain only digits (0-9)", StockParameter::WARRANTY_KMS)
            )
            ->default(null)
            ->getValue());
        
      
        $stockData->setSoldFlag($requestParameters->get(StockParameter::SOLD_FLAG)->default(ValidationConstants::SOLD_FLAG_DEFAULT)->getValue());

        return $stockData;
    }

    /**
     * Obtiene los parametros y verifica cuales son requeridos para poder crear la entidad.
     *
     * @param string $dsn
     * @param RequestParameter $requestParameters
     *
     * @return StockEntity
     */
    public function loadEntityFromParamsOrFail(string $dsn, RequestParameter $requestParameters): StockEntity
    {
        $connection = $this->container->get(Sql::class)->open($dsn, 'hub');
        $model = new StockModel($connection);
        $stockEntity = $model->newEmptyEntity();

        $stockEntity->setProduct_vehicle_id($requestParameters->get(StockParameter::PRODUCT_VEHICLE_ID)
            ->required(sprintf("Field '%s' is required", StockParameter::PRODUCT_VEHICLE_ID))
            ->getValue());
        $stockEntity->setVehicle_type_id($requestParameters->get(StockParameter::VEHICLE_TYPE_ID)
            ->required(sprintf("Field '%s' is required", StockParameter::VEHICLE_TYPE_ID))
            ->getValue());
        $stockEntity->setVehicle_identification_number($requestParameters->get(StockParameter::VEHICLE_IDENTIFICATION_NUMBER)
            ->required(sprintf("Field '%s' is required", StockParameter::VEHICLE_IDENTIFICATION_NUMBER))
            ->assertRegex(DataTypeConstants::VALID_LENGTH_20_PATTERN, sprintf("'%s' field not valid. Must be 20 characters max", StockParameter::VEHICLE_IDENTIFICATION_NUMBER))
            ->assertAlphanumeric(sprintf("'%s' field not valid. Must contain letters (a-z) and digits (0-9)", StockParameter::VEHICLE_IDENTIFICATION_NUMBER))
            ->getValue());
        $stockEntity->setChassis_number($requestParameters->get(StockParameter::CHASSIS_NUMBER)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_100_PATTERN, sprintf("'%s' field not valid. Must be 100 characters max", StockParameter::CHASSIS_NUMBER))
            ->assertRegex(DataTypeConstants::VALID_CHASSIS_NUMBER_PATTERN, sprintf("'%s' field not valid. Must contain letters (a-z), digits (0-9) and -\\", StockParameter::CHASSIS_NUMBER))
            ->default(null)
            ->getValue());
        $stockEntity->setEngine_number($requestParameters->get(StockParameter::ENGINE_NUMBER)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_100_PATTERN, sprintf("'%s' field not valid. Must be 100 characters max", StockParameter::ENGINE_NUMBER))
            ->assertRegex(DataTypeConstants::VALID_ENGINE_NUMBER_PATTERN, sprintf("'%s' field not valid. Must contain letters (a-z), digits (0-9), -_ and /", StockParameter::ENGINE_NUMBER))
            ->default(null)
            ->getValue());
        $stockEntity->setFactory_order_number($requestParameters->get(StockParameter::FACTORY_ORDER_NUMBER)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_100_PATTERN, sprintf("'%s' field not valid. Must be 100 characters max", StockParameter::FACTORY_ORDER_NUMBER))
            ->default(null)
            ->getValue());
        $stockEntity->setDealer_invoice_date($requestParameters->get(StockParameter::DEALER_INVOICE_NUMBER)
            ->assertRegex(DataTypeConstants::VALID_DATE_PATTERN, sprintf("'%s' field not valid. Must be a valid date format (YYYY-MM-DD)", StockParameter::DEALER_INVOICE_DATE))
            ->assertDate(sprintf("'%s' field not valid. Must be a valid date format (YYYY-MM-DD)", StockParameter::DEALER_INVOICE_DATE))
            ->default(null)
            ->getValue());
        $stockEntity->setDealer_invoice_number($requestParameters->get(StockParameter::DEALER_INVOICE_NUMBER)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_50_PATTERN, sprintf("'%s' field not valid. Must be 50 characters max", StockParameter::DEALER_INVOICE_NUMBER))
            ->assertRegex(DataTypeConstants::VALID_INVOICE_NUMBER_PATTERN, sprintf("'%s' field not valid. Must contain letters (a-z), digits (0-9) and -", StockParameter::DEALER_INVOICE_NUMBER))
            ->default(null)
            ->getValue());
        $stockEntity->setSales_channel($requestParameters->get(StockParameter::SALES_CHANNEL)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_50_PATTERN, sprintf("'%s' field not valid. Must be 50 characters max", StockParameter::SALES_CHANNEL))
            ->default(null)
            ->getValue());
        $stockEntity->setColor($requestParameters->get(StockParameter::COLOR)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_50_PATTERN, sprintf("'%s' field not valid. Must be 50 characters max", StockParameter::COLOR))
            ->default(null)
            ->getValue());
        $stockEntity->setFactory_state($requestParameters->get(StockParameter::FACTORY_STATE)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_50_PATTERN, sprintf("'%s' field not valid. Must be 50 characters max", StockParameter::FACTORY_STATE))
            ->default(null)
            ->getValue());
        $stockEntity->setDealer_id($requestParameters->get(StockParameter::DEALER_ID)
            ->required("Field 'dealer_id' is required")
            ->getValue());
        $stockEntity->setStatus_id($requestParameters->get(StockParameter::STATUS_ID)
            ->required("Field 'status_id' is required")
            ->getValue());

        $stockEntity->setSale_process_id($requestParameters->get(StockParameter::SALE_PROCESS_ID)
            ->default(null)
            ->getValue());

        $stockEntity->setSold_date($requestParameters->get(StockParameter::SOLD_DATE)
            ->assertRegex(DataTypeConstants::VALID_DATE_PATTERN, sprintf("'%s' field not valid. Must be a valid date format (YYYY-MM-DD)", StockParameter::SOLD_DATE))
            ->default(null)
            ->getValue());
        
        $stockEntity->setWarranty_is_active_flag($requestParameters->get(StockParameter::WARRANTY_IS_ACTIVE_FLAG)
            ->assertRegex(DataTypeConstants::VALID_BOOL_PATTERN, sprintf("'%s' field not valid. Must be 0 or 1", StockParameter::WARRANTY_IS_ACTIVE_FLAG))
            ->default(0)
            ->getValue());
        $stockEntity->setWarranty_start_date($requestParameters->get(StockParameter::WARRANTY_START_DATE)
            ->assertRegex(DataTypeConstants::VALID_DATE_PATTERN, sprintf("'%s' field not valid. Must be a valid date format (YYYY-MM-DD)", StockParameter::WARRANTY_START_DATE))
            ->default(null)
            ->getValue());
        $stockEntity->setWarranty_end_date($requestParameters->get(StockParameter::WARRANTY_END_DATE)
            ->assertRegex(DataTypeConstants::VALID_DATE_PATTERN, sprintf("'%s' field not valid. Must be a valid date format (YYYY-MM-DD)", StockParameter::WARRANTY_END_DATE))
            ->default(null)
            ->getValue());
        $stockEntity->setWarranty_notes($requestParameters->get(StockParameter::WARRANTY_NOTES)
            ->assertRegex(DataTypeConstants::VALID_LENGTH_500_PATTERN, sprintf("'%s' field not valid. Must be 500 characters max", StockParameter::DEALER_CODE))
            ->default(null)
            ->getValue());
        $stockEntity->setWarranty_Kms($requestParameters->get(StockParameter::WARRANTY_KMS)
            ->assertNumeric(
                sprintf("'%s' field not valid. Must contain only digits (0-9)", StockParameter::DEALER_CODE)
            )
            ->default(null)
            ->getValue());

        return $stockEntity;
    }
}
