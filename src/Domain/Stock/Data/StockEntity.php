<?php

namespace App\Domain\Stock\Data;

use Pilot\Component\Sql\Entity;

final class StockEntity extends Entity
{
    public function loadFromState(array $data = []): self
    {
        if (!empty($data)) {
            $this->setId($data[StockParameter::ID] ?? null);
            $this->setProduct_vehicle_id($data[StockParameter::PRODUCT_VEHICLE_ID] ?? null);
            $this->setVehicle_type_id($data[StockParameter::VEHICLE_TYPE_ID] ?? null);
            $this->setVehicle_identification_number($data[StockParameter::VEHICLE_IDENTIFICATION_NUMBER] ?? null);
            $this->setChassis_number($data[StockParameter::CHASSIS_NUMBER] ?? null);
            $this->setEngine_number($data[StockParameter::ENGINE_NUMBER] ?? null);
            $this->setFactory_order_number($data[StockParameter::FACTORY_ORDER_NUMBER] ?? null);
            $this->setDealer_invoice_date($data[StockParameter::DEALER_INVOICE_DATE] ?? null);
            $this->setDealer_invoice_number($data[StockParameter::DEALER_INVOICE_NUMBER] ?? null);
            $this->setSales_channel($data[StockParameter::SALES_CHANNEL] ?? null);
            $this->setColor($data[StockParameter::COLOR] ?? null);
            $this->setFactory_state($data[StockParameter::FACTORY_STATE] ?? null);
            $this->setDealer_id($data[StockParameter::DEALER_ID] ?? null);
            $this->setStatus_id($data[StockParameter::STATUS_ID] ?? null);
            $this->setSale_process_id($data[StockParameter::SALE_PROCESS_ID] ?? null);
            $this->setSold_flag($data[StockParameter::SOLD_FLAG] ?? 0);
            $this->setSold_date($data[StockParameter::SOLD_DATE] ?? null);
            $this->setWarranty_is_active_flag($data[StockParameter::WARRANTY_IS_ACTIVE_FLAG] ?? 0);
            $this->setWarranty_start_date($data[StockParameter::WARRANTY_START_DATE] ?? null);
            $this->setWarranty_end_date($data[StockParameter::WARRANTY_END_DATE] ?? null);
            $this->setWarranty_notes($data[StockParameter::WARRANTY_NOTES] ?? null);
            $this->setWarranty_kms($data[StockParameter::WARRANTY_KMS] ?? null);
            $this->setDeleted($data[StockParameter::DELETED] ?? 0);
            $this->setAudit_created_user_id($data[StockParameter::AUDIT_CREATED_USER_ID] ?? null);
            $this->setAudit_updated_user_id($data[StockParameter::AUDIT_UPDATED_USER_ID] ?? null);
            $this->setAudit_deleted_user_id($data[StockParameter::AUDIT_DELETED_USER_ID] ?? null);
            $this->setAudit_created_dt($data[StockParameter::AUDIT_CREATED_DT] ?? null);
            $this->setAudit_updated_dt($data[StockParameter::AUDIT_UPDATED_DT] ?? null);
            $this->setAudit_deleted_dt($data[StockParameter::AUDIT_DELETED_DT] ?? null);
        }

        return $this;
    }
}
