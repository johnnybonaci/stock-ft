<?php

namespace App\Domain\StockImport\Repository;

/* Archivo generado automaticamente por pilot-cli */
use Pilot\Component\Sql\Model;

class StockImportModel extends Model
{
    protected array $model = [
        'table' => 'hub_log_factory_stock_import',
        'phpName' => "\App\Domain\StockImport\Data\StockImportEntity",
        'columns' => [
            ['name' => 'id', 'phpName' => 'id', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'primaryKey' => true, 'autoIncrement' => true, 'required' => true],
            ['name' => 'input_file_s3_key', 'phpName' => 'input_file_s3_key', 'type' => Model::DATA_TYPE_STRING, 'size' => 250, 'required' => true],
            ['name' => 'request_dt', 'phpName' => 'request_dt', 'type' => Model::DATA_TYPE_AUDIT_DT, 'size' => 23, 'required' => true,  'saveOn' => ['create']],
            ['name' => 'request_user_id', 'phpName' => 'request_user_id', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'required' => true],
            ['name' => 'request_user_name', 'phpName' => 'request_user_name', 'type' => Model::DATA_TYPE_STRING, 'size' => 50, 'required' => true],
            ['name' => 'request_status_code', 'phpName' => 'request_status_code', 'type' => Model::DATA_TYPE_STRING, 'size' => 10, 'required' => true],
            ['name' => 'import_records_qty', 'phpName' => 'import_records_qty', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'required' => false, 'default' => null],
            ['name' => 'output_log_file_s3_key', 'phpName' => 'output_log_file_s3_key', 'type' => Model::DATA_TYPE_STRING, 'size' => 250, 'required' => false, 'default' => null],
            ['name' => 'import_end_dt', 'phpName' => 'import_end_dt', 'type' => Model::DATA_TYPE_AUDIT_DT, 'size' => 23, 'required' => false, 'saveOn' => ['update'],  'default' => null],
        ],
    ];
}
