<?php

namespace App\Domain\Stock\Data;

final class StockData
{
    public ?int $id = null;
    public ?int $product_vehicle_id = null;
    public ?string $product_vehicle_code = null;
    public ?int $vehicle_type_id = null;
    public ?string $vehicle_type_code = null;
    public ?string $vehicle_identification_number = null;
    public ?string $chassis_number = null;
    public ?string $engine_number = null;
    public ?string $factory_order_number = null;
    public ?string $dealer_invoice_date = null;
    public ?string $dealer_invoice_number = null;
    public ?string $sales_channel = null;
    public ?string $color = null;
    public ?string $factory_state = null;
    public ?int $dealer_id = null;
    public ?string $dealer_code = null;
    public ?int $status_id = null;
    public ?string $status_code = null;
    public ?int $sale_process_id = null;
    public ?string $sale_id = null;
    public ?int $sold_flag = null;
    public ?string $sold_date = null;
    public ?int $warranty_is_active_flag = null;
    public ?string $warranty_start_date = null;
    public ?string $warranty_end_date = null;
    public ?string $warranty_notes = null;
    public ?int $warranty_kms = null;
    public ?int $deleted = null;
    public ?int $audit_created_user_id = null;
    public ?int $audit_updated_user_id = null;
    public ?int $audit_deleted_user_id = null;
    public ?string $audit_created_dt = null;
    public ?string $audit_updated_dt = null;
    public ?string $audit_deleted_dt = null;

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function loadFromState(array $data = []): self
    {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getProductVehicleId(): ?int
    {
        return $this->product_vehicle_id;
    }

    public function setProductVehicleId(?int $product_vehicle_id): self
    {
        $this->product_vehicle_id = $product_vehicle_id;

        return $this;
    }

    public function getProductVehicleCode(): ?string
    {
        return $this->product_vehicle_code;
    }

    public function setProductVehicleCode(?string $product_vehicle_code): self
    {
        $this->product_vehicle_code = $product_vehicle_code;

        return $this;
    }

    public function getVehicleTypeId(): ?int
    {
        return $this->vehicle_type_id;
    }

    public function setVehicleTypeId(?int $vehicle_type_id): self
    {
        $this->vehicle_type_id = $vehicle_type_id;

        return $this;
    }

    public function getVehicleTypeCode(): ?string
    {
        return $this->vehicle_type_code;
    }

    public function setVehicleTypeCode(?string $vehicle_type_code): self
    {
        $this->vehicle_type_code = $vehicle_type_code;

        return $this;
    }

    public function getVehicleIdentificationNumber(): ?string
    {
        return $this->vehicle_identification_number;
    }

    public function setVehicleIdentificationNumber(?string $vehicle_identification_number): self
    {
        $this->vehicle_identification_number = $vehicle_identification_number;

        return $this;
    }

    public function getChassisNumber(): ?string
    {
        return $this->chassis_number;
    }

    public function setChassisNumber(?string $chassis_number): self
    {
        $this->chassis_number = $chassis_number;

        return $this;
    }

    public function getEngineNumber(): ?string
    {
        return $this->engine_number;
    }

    public function setEngineNumber(?string $engine_number): self
    {
        $this->engine_number = $engine_number;

        return $this;
    }

    public function getFactoryOrderNumber(): ?string
    {
        return $this->factory_order_number;
    }

    public function setFactoryOrderNumber(?string $factory_order_number): self
    {
        $this->factory_order_number = $factory_order_number;

        return $this;
    }

    public function getDealerInvoiceDate(): ?string
    {
        return $this->dealer_invoice_date;
    }

    public function setDealerInvoiceDate(?string $dealer_invoice_date): self
    {
        $this->dealer_invoice_date = $dealer_invoice_date;

        return $this;
    }

    public function getDealerInvoiceNumber(): ?string
    {
        return $this->dealer_invoice_number;
    }

    public function setDealerInvoiceNumber(?string $dealer_invoice_number): self
    {
        $this->dealer_invoice_number = $dealer_invoice_number;

        return $this;
    }

    public function getSalesChannel(): ?string
    {
        return $this->sales_channel;
    }

    public function setSalesChannel(?string $sales_channel): self
    {
        $this->sales_channel = $sales_channel;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getFactoryState(): ?string
    {
        return $this->factory_state;
    }

    public function setFactoryState(?string $factory_state): self
    {
        $this->factory_state = $factory_state;

        return $this;
    }

    public function getDealerId(): ?int
    {
        return $this->dealer_id;
    }

    public function setDealerId(?int $dealer_id): self
    {
        $this->dealer_id = $dealer_id;

        return $this;
    }

    public function getDealerCode(): ?string
    {
        return $this->dealer_code;
    }

    public function setDealerCode(?string $dealer_code): self
    {
        $this->dealer_code = $dealer_code;

        return $this;
    }

    public function getStatusId(): ?int
    {
        return $this->status_id;
    }

    public function setStatusId(?int $status_id): self
    {
        $this->status_id = $status_id;

        return $this;
    }

    public function getStatusCode(): ?string
    {
        return $this->status_code;
    }

    public function setStatusCode(?string $status_code): self
    {
        $this->status_code = $status_code;

        return $this;
    }

    public function getSaleProcessId(): ?int
    {
        return $this->sale_process_id;
    }

    public function setSaleProcessId(?int $sale_process_id): self
    {
        $this->sale_process_id = $sale_process_id;

        return $this;
    }

    public function getSaleId(): ?string
    {
        return $this->sale_id;
    }

    public function setSaleId(?string $sale_id): self
    {
        $this->sale_id = $sale_id;

        return $this;
    }

    public function getSoldFlag(): ?int
    {
        return $this->sold_flag;
    }

    public function setSoldFlag(?int $sold_flag): self
    {
        $this->sold_flag = $sold_flag;

        return $this;
    }

    public function getSoldDate(): ?string
    {
        return $this->sold_date;
    }

    public function setSoldDate(?string $sold_date): self
    {
        $this->sold_date = $sold_date;

        return $this;
    }

    public function getWarrantyIsActiveFlag(): ?int
    {
        return $this->warranty_is_active_flag;
    }

    public function setWarrantyIsActiveFlag(?int $warranty_is_active_flag): self
    {
        $this->warranty_is_active_flag = $warranty_is_active_flag;

        return $this;
    }

    public function getWarrantyStartDate(): ?string
    {
        return $this->warranty_start_date;
    }

    public function setWarrantyStartDate(?string $warranty_start_date): self
    {
        $this->warranty_start_date = $warranty_start_date;

        return $this;
    }

    public function getWarrantyEndDate(): ?string
    {
        return $this->warranty_end_date;
    }

    public function setWarrantyEndDate(?string $warranty_end_date): self
    {
        $this->warranty_end_date = $warranty_end_date;

        return $this;
    }

    public function getWarrantyNotes(): ?string
    {
        return $this->warranty_notes;
    }

    public function setWarrantyNotes(?string $warranty_notes): self
    {
        $this->warranty_notes = $warranty_notes;

        return $this;
    }

    public function getWarrantyKms(): ?int
    {
        return $this->warranty_kms;
    }

    public function setWarrantyKms(?int $warranty_kms): self
    {
        $this->warranty_kms = $warranty_kms;

        return $this;
    }

    public function getDeleted(): ?int
    {
        return $this->deleted;
    }

    public function setDeleted(?int $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getAuditCreatedUserId(): ?int
    {
        return $this->audit_created_user_id;
    }

    public function setAuditCreatedUserId(?int $audit_created_user_id): self
    {
        $this->audit_created_user_id = $audit_created_user_id;

        return $this;
    }

    public function getAuditUpdatedUserId(): ?int
    {
        return $this->audit_updated_user_id;
    }

    public function setAuditUpdatedUserId(?int $audit_updated_user_id): self
    {
        $this->audit_updated_user_id = $audit_updated_user_id;

        return $this;
    }

    public function getAuditDeletedUserId(): ?int
    {
        return $this->audit_deleted_user_id;
    }

    public function setAuditDeletedUserId(?int $audit_deleted_user_id): self
    {
        $this->audit_deleted_user_id = $audit_deleted_user_id;

        return $this;
    }

    public function getAuditCreatedDt(): ?string
    {
        return $this->audit_created_dt;
    }

    public function setAuditCreatedDt(?string $audit_created_dt): self
    {
        $this->audit_created_dt = $audit_created_dt;

        return $this;
    }

    public function getAuditUpdatedDt(): ?string
    {
        return $this->audit_updated_dt;
    }

    public function setAuditUpdatedDt(?string $audit_updated_dt): self
    {
        $this->audit_updated_dt = $audit_updated_dt;

        return $this;
    }

    public function getAuditDeletedDt(): ?string
    {
        return $this->audit_deleted_dt;
    }

    public function setAuditDeletedDt(?string $audit_deleted_dt): self
    {
        $this->audit_deleted_dt = $audit_deleted_dt;

        return $this;
    }
}
