<?php

namespace Wechalet\TaxIdentifier;


use Wechalet\TaxIdentifier\Enum\TaxType;
use Wechalet\TaxIdentifier\Exception\InvalidTaxFormat;
use Wechalet\TaxIdentifier\Exception\InvalidTaxRate;
use Wechalet\TaxIdentifier\Exception\InvalidTaxType;
use Wechalet\TaxIdentifier\Types\TaxExemptInvoiceLineItem;

abstract class TaxIdentifier implements TaxInterface
{
    protected string $id;
    protected ?string $name = null;
    protected float $rate = 0.0;
    protected string $type;

    public function __construct(string $id)
    {
        if (!$this->isValidFormat($id)) {
            throw new InvalidTaxFormat();
        }

        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * @throws InvalidTaxRate
     */
    public function setRate($rate): void
    {
        if($rate > 100 && $this->type == TaxType::TAX_TYPE_RATIO){
            throw new InvalidTaxRate();
        }
        
        $this->rate = $rate;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function applyTo(Bill $bill): InvoiceLine
    {
        $taxAmount = 0;

        foreach ($bill->items as $item) {
            if (is_a($item->getType(), TaxExemptInvoiceLineItem::class)) {
                continue;
            }
            $taxAmount += $this->applyTax( $item->getTotal() );
        }

        return new InvoiceLineTax(
            $this->getName(),
            $taxAmount
        );
    }

    /**
     * @throws InvalidTaxType
     */
    protected function applyTax(float $amount): float
    {
        if(empty($this->getRate()) && $this->type == TaxType::TAX_TYPE_RATIO)
            return 0;

        switch ($this->type) {
            case TaxType::TAX_TYPE_FIXED:
                return $amount + $this->getRate();
            case TaxType::TAX_TYPE_RATIO:
                return $amount * $this->getRate() / 100;
            default:
                throw new InvalidTaxType();
        }
    }
}
