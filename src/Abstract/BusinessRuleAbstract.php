<?php

namespace App\Abstract;

use App\Domain\Stock\Data\StockData;
use App\Interface\BusinessRuleInterface;
use Psr\Container\ContainerInterface;

abstract class BusinessRuleAbstract implements BusinessRuleInterface
{
    protected ?BusinessRuleInterface $nextRule = null;

    public function __construct(protected ContainerInterface $container)
    {
    }

    protected function getDsn(): string
    {
        return (string)$this->container->get('dsn');
    }

    public function addRule(BusinessRuleInterface $next): BusinessRuleInterface
    {
        $this->nextRule = $next;

        return $next;
    }

    public function apply(StockData $data): StockData
    {
        if ($this->nextRule) {
            return $this->nextRule->apply($data);
        }

        return $data;
    }
}
