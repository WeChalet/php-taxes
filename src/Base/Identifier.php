<?php

namespace Wechalet\TaxIdentifier\Base;


use Wechalet\TaxIdentifier\Enum\IdentifierType;
use Wechalet\TaxIdentifier\Exception\InvalidTaxType;

abstract class Identifier
{
    protected ?array $applied = [];
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
        if(empty($this->getRate()) && $this->type == IdentifierType::TYPE_RATIO)
            return 0;

        switch ($this->type)
        {
            case IdentifierType::TYPE_FIXED:
                return $amount - $this->getRate() >= 0 ? $this->getRate() : $amount;
            case IdentifierType::TYPE_RATIO:
                return $amount * $this->getRate() / 100;
            default:
                throw new InvalidTaxType();
        }
    }

    public function applyOn($data = null): self
    {
        if (is_array($data))
            $this->applied = array_merge($this->applied, $data);
        else
            $this->applied[] = $data;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'rate' => $this->rate,
            'type' => $this->type,
        ];
    }
}
