<?php

namespace Wechalet\TaxIdentifier;


use Wechalet\TaxIdentifier\Base\Identifier;
use Wechalet\TaxIdentifier\Base\InvoiceLine;
use Wechalet\TaxIdentifier\Enum\DiscountType;

abstract class DiscountIdentifier extends Identifier
{
    protected ?array $deductible = [];
    protected string $discount_type = DiscountType::TAX_DEDUCTIBLE;

    public function applyTo(Bill $bill): InvoiceLine
    {
        $discountAmount = 0;

        foreach ($bill->items as $item)
        {
            if ($this->deductible && !in_array($item->getTitle() ,$this->deductible))
                continue;

            $item->addDiscount(
                $discount = $this->apply( $item->getTotal() )
            );

            $discountAmount += $discount;
        }

        return new InvoiceLineDiscount(
            $this->getName(),
            $discountAmount
        );
    }

    public function applyOn(?Array $items = null): self
    {
        $this->deductible = $items;

        return $this;
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
}