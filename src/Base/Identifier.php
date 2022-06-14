<?php

namespace Wechalet\TaxIdentifier\Base;


use Wechalet\TaxIdentifier\Enum\TaxType;
use Wechalet\TaxIdentifier\Exception\InvalidTaxFormat;
use Wechalet\TaxIdentifier\Exception\InvalidTaxType;
use Wechalet\TaxIdentifier\Interfaces\TaxInterface;

abstract class Identifier implements TaxInterface
{
    protected string $name;
    protected float $rate = 0.0;
    protected string $type;

    public function getName(): string
    {
        return $this->name;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * @throws InvalidTaxType
     */
    protected function apply(float $amount): float
    {
        if(empty($this->getRate()) && $this->type == TaxType::TAX_TYPE_RATIO)
            return 0;

        switch ($this->type)
        {
            case TaxType::TAX_TYPE_FIXED:
                return $amount + $this->getRate();
            case TaxType::TAX_TYPE_RATIO:
                return $amount * $this->getRate() / 100;
            default:
                throw new InvalidTaxType();
        }
    }
}
