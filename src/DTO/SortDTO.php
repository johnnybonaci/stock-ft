<?php

namespace App\DTO;

class SortDTO
{
    public string $field;
    public string $order;

    public function __construct(
        string $field,
        string $order,
    ) {
        $this->field = $field;
        $this->order = $order;
    }
}
