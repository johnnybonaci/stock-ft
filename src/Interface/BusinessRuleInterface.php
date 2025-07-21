<?php

namespace App\Interface;

use App\Domain\Stock\Data\StockData;

interface BusinessRuleInterface
{
    public function addRule(BusinessRuleInterface $next): BusinessRuleInterface;

    public function apply(StockData $data): StockData;
}
