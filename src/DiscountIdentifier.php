<?php

namespace Wechalet\TaxIdentifier;


use Wechalet\TaxIdentifier\Base\Identifier;
use Wechalet\TaxIdentifier\Base\InvoiceLine;
use Wechalet\TaxIdentifier\Enum\DiscountType;

abstract class DiscountIdentifier extends Identifier
{
    protected string $discount_type = DiscountType::TAX_DEDUCTIBLE;

    public function applyTo(Bill $bill): InvoiceLine
    {
        $total = 0.0;

        foreach ($bill->items as $item)
        {
            if (!empty($this->applied) && !in_array($item->getTitle() ,$this->applied))
                continue;

            $total += $item->getTotal() + ( !$this->is(DiscountType::NONE_TAX_DEDUCTIBLE) ? $item->getTax() : 0.0);
        }

        $discountAmount = $this->apply($total);

        return new InvoiceLineDiscount(
            $this->getName(),
            $discountAmount
        );
    }

    public function setNoneTaxDeductibleDiscount(): self
    {
        $this->discount_type = DiscountType::NONE_TAX_DEDUCTIBLE;

        return $this;
    }

    public function setTaxDeductibleDiscount(): self
    {
        $this->discount_type = DiscountType::TAX_DEDUCTIBLE;

        return $this;
    }

    public function is($type): bool
    {
        return $this->discount_type === $type;
    }
}