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
        $total = 0.0;

        foreach ($bill->items as $item)
        {
            if (!empty($this->deductible) && !in_array($item->getTitle() ,$this->deductible))
                continue;

            $total += $item->getTotal() + ( !$this->is(DiscountType::NONE_TAX_DEDUCTIBLE) ? $item->getTax() : 0.0);
        }

        $discountAmount = $this->apply($total);

        return new InvoiceLineDiscount(
            $this->getName(),
            $discountAmount
        );
    }

    public function applyOn($data = null): self
    {
        if (is_array($data))
            $this->deductible = array_merge($this->deductible, $data);
        else
            $this->deductible[] = $data;

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

    public function is($type): bool
    {
        return $this->discount_type === $type;
    }
}