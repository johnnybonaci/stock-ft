<?php

namespace App\DTO;

class FilterDTO
{
    public string $field;
    public string $operation;
    public string $value;

    public function __construct(
        string $field,
        string $operation,
        string $value,
    ) {
        $this->field = $field;
        $this->operation = $operation;
        $this->value = $value;
    }
}
