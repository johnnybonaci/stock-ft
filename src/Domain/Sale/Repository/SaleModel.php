<?php

namespace App\Domain\Sale\Repository;

use Pilot\Component\Sql\Model;

class SaleModel extends Model
{
    protected array $model = [
        'table' => 'hub_ft_sale_processes',
        'phpName' => "\App\Domain\Sale\Data\SaleEntity",
        'columns' => [
            ['name' => 'id', 'phpName' => 'id', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 4, 'required' => false, 'primaryKey' => true],
            ['name' => 'guid', 'phpName' => 'guid', 'type' => Model::DATA_TYPE_STRING, 'size' => 37, 'required' => false],
            ['name' => 'dealer_id', 'phpName' => 'dealer_id', 'type' => Model::DATA_TYPE_INTEGER, 'size' => 10, 'required' => false],
            ['name' => 'created_date', 'phpName' => 'created_date', 'type' => Model::DATA_TYPE_DATE, 'required' => false],
            ['name' => 'sale_date', 'phpName' => 'sale_date', 'type' => Model::DATA_TYPE_DATE, 'required' => false],
        ],
    ];
}
