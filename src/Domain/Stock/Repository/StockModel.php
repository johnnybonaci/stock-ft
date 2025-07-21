<?php

namespace App\Domain\Stock\Repository;

/* Archivo generado automaticamente por pilot-cli */
use Pilot\Component\Sql\Model;

class StockModel extends Model
{
    protected array $model = [
        'table' => 'hub_ft_factory_stock',
        'phpName' => "\App\Domain\Stock\Data\StockEntity",
        'columns' => [
            ['name' => 'id', 'phpName' => 'id', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'primaryKey' => true, 'autoIncrement' => true, 'required' => true],
            ['name' => 'product_vehicle_id', 'phpName' => 'product_vehicle_id', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'required' => true,
                "fk"=> [
                    "join" => "left",
                    "foreignTable" => "hub_lk_product_vehicles",
                    "foreignKey" => "id", 
                    "columns" => [
                        ["name" => "code", "phpName" => "product_vehicle_code"],
                    ]
                ]
            ],
            ['name' => 'vehicle_type_id', 'phpName' => 'vehicle_type_id', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'required' => true,
                "fk"=> [
                    "join" => "left",
                    "foreignTable" => "hub_lk_stock_vehicles_types",
                    "foreignKey" => "id", 
                    "columns" => [
                        ["name" => "code", "phpName" => "vehicle_type_code"],
                        ["name" => "name", "phpName" => "vehicle_type_name"],

                    ]
                ]
            ],
            ['name' => 'vehicle_identification_number', 'phpName' => 'vehicle_identification_number', 'type' => Model::DATA_TYPE_STRING, 'size' => 100, 'required' => true],
            ['name' => 'chassis_number', 'phpName' => 'chassis_number', 'type' => Model::DATA_TYPE_STRING, 'size' => 100, 'required' => false],
            ['name' => 'engine_number', 'phpName' => 'engine_number', 'type' => Model::DATA_TYPE_STRING, 'size' => 100, 'required' => false],
            ['name' => 'factory_order_number', 'phpName' => 'factory_order_number', 'type' => Model::DATA_TYPE_STRING, 'size' => 100, 'required' => false],
            ['name' => 'dealer_invoice_date', 'phpName' => 'dealer_invoice_date', 'type' => Model::DATA_TYPE_DATE, 'size' => 23, 'required' => false],
            ['name' => 'dealer_invoice_number', 'phpName' => 'dealer_invoice_number', 'type' => Model::DATA_TYPE_STRING, 'size' => 50, 'required' => false],
            ['name' => 'sales_channel', 'phpName' => 'sales_channel', 'type' => Model::DATA_TYPE_STRING, 'size' => 50, 'required' => false],
            ['name' => 'color', 'phpName' => 'color', 'type' => Model::DATA_TYPE_STRING, 'size' => 50, 'required' => false],
            ['name' => 'factory_state', 'phpName' => 'factory_state', 'type' => Model::DATA_TYPE_STRING, 'size' => 50, 'required' => false],
            ['name' => 'dealer_id', 'phpName' => 'dealer_id', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'required' => true,
                "fk"=> [
                    "join" => "left",
                    "foreignTable" => "hub_lk_dealers",
                    "foreignKey" => "id", 
                    "columns" => [
                        ["name" => "name", "phpName" => "dealer_name"],
                        ["name" => "code", "phpName" => "dealer_code"],
                    ]
                ]
            ],
            ['name' => 'status_id', 'phpName' => 'status_id', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'required' => true,
                "fk"=> [
                    "join" => "left",
                    "foreignTable" => "hub_lk_stock_status",
                    "foreignKey" => "id", 
                    "columns" => [
                        ["name" => "name", "phpName" => "status_name"],
                        ["name" => "code", "phpName" => "status_code"],
                    ]
                ]
            ],
            ['name' => 'sale_process_id', 'phpName' => 'sale_process_id', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'required' => false],
            ['name' => 'sold_flag', 'phpName' => 'sold_flag', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'required' => false],
            ['name' => 'sold_date', 'phpName' => 'sold_date', 'type' => Model::DATA_TYPE_DATE, 'size' => 23, 'required' => false],
            ['name' => 'warranty_is_active_flag', 'phpName' => 'warranty_is_active_flag', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'required' => false],
            ['name' => 'warranty_start_date', 'phpName' => 'warranty_start_date', 'type' => Model::DATA_TYPE_DATE, 'size' => 23, 'required' => false],
            ['name' => 'warranty_end_date', 'phpName' => 'warranty_end_date', 'type' => Model::DATA_TYPE_DATE, 'size' => 23, 'required' => false],
            ['name' => 'warranty_notes', 'phpName' => 'warranty_notes', 'type' => Model::DATA_TYPE_STRING, 'size' => 500, 'required' => false],
            ['name' => 'warranty_kms', 'phpName' => 'warranty_kms', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'required' => false],
            ['name' => 'deleted', 'phpName' => 'deleted', 'type' => Model::DATA_TYPE_DELETE_FLAG, 'size' => 10, 'required' => false],
            ['name' => 'audit_created_user_id', 'phpName' => 'audit_created_user_id', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'required' => true],
            ['name' => 'audit_updated_user_id', 'phpName' => 'audit_updated_user_id', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'required' => false],
            ['name' => 'audit_deleted_user_id', 'phpName' => 'audit_deleted_user_id', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'required' => false],
            ['name' => 'audit_created_dt', 'phpName' => 'audit_created_dt', 'type' => Model::DATA_TYPE_AUDIT_DT, 'size' => 23, 'required' => true,  'saveOn' => ['create']],
            ['name' => 'audit_updated_dt', 'phpName' => 'audit_updated_dt', 'type' => Model::DATA_TYPE_DATETIME, 'size' => 23, 'required' => false, ],
            ['name' => 'audit_deleted_dt', 'phpName' => 'audit_deleted_dt', 'type' => Model::DATA_TYPE_DATETIME, 'size' => 23, 'required' => false, ],
        ],
    ];
}
