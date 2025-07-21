<?php

namespace App\Domain\Stock\Service;

use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockEntity;
use App\Domain\Stock\Repository\UpdateRepository;

/**
 * Update Service.
 */
final class UpdateService
{
    /**
     * The constructor.
     *
     * @param UpdateRepository $repository The repository
     * @param ReadService $serviceRead Service designed to read a given entity
     */
    public function __construct(
        private UpdateRepository $repository,
        private ReadService $serviceRead,
    ) {
    }

    /**
     * Returns the updated Stock.
     *
     * @param string $dsn Name of Pilot's instance
     * @param int $id Entity identifier
     * @param StockData $newData Represents the new state of the Stock
     *
     * @return StockEntity
     */
    public function update(string $dsn, int $id, StockData $newData): StockEntity
    {
        $currentEntity = $this->serviceRead->read($dsn, $id);
        $currentEntity->setProduct_vehicle_id($newData->getProductVehicleId());
        $currentEntity->setVehicle_type_id($newData->getVehicleTypeId());
        $currentEntity->setVehicle_identification_number($newData->getVehicleIdentificationNumber());
        $currentEntity->setChassis_number($newData->getChassisNumber());
        $currentEntity->setEngine_number($newData->getEngineNumber());
        $currentEntity->setFactory_order_number($newData->getFactoryOrderNumber());
        $currentEntity->setDealer_invoice_date($newData->getDealerInvoiceDate());
        $currentEntity->setDealer_invoice_number($newData->getDealerInvoiceNumber());
        $currentEntity->setSales_channel($newData->getSalesChannel());
        $currentEntity->setColor($newData->getColor());
        $currentEntity->setFactory_state($newData->getFactoryState());
        $currentEntity->setDealer_id($newData->getDealerId());
        $currentEntity->setStatus_id($newData->getStatusId());
        $currentEntity->setSale_process_id($newData->getSaleProcessId());
        $currentEntity->setSold_flag($newData->getSoldFlag());
        $currentEntity->setSold_date($newData->getSoldDate());
        $currentEntity->setwarranty_is_active_flag($newData->getWarrantyIsActiveFlag() ?? 0);
        $currentEntity->setWarranty_start_date($newData->getWarrantyStartDate());
        $currentEntity->setWarranty_end_date($newData->getWarrantyEndDate());
        $currentEntity->setWarranty_notes($newData->getWarrantyNotes());
        $currentEntity->setWarranty_kms($newData->getWarrantyKms());
        // $currentEntity->setDeleted($newData->getDeleted() ?? 0);
        $currentEntity->setAudit_updated_user_id($newData->getAuditUpdatedUserId());
        // $currentEntity->setAudit_deleted_user_id($newData->getAuditDeletedUserId());

        return $this->repository->update($dsn, $currentEntity);
    }
}
