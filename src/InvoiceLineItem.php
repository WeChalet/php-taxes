<?php

namespace Wechalet\TaxIdentifier;

use Wechalet\TaxIdentifier\Base\InvoiceLine;
use Wechalet\TaxIdentifier\Types\InvoiceLineItemType;

class InvoiceLineItem extends InvoiceLine
{
    protected InvoiceLineItemType $type;
    protected int $quantity = 1;
    protected float $discount = 0.0;

    public function __construct(InvoiceLineItemType $type, string $title, float $price, int $quantity, ?string $measure)
    {
        parent::__construct($title, $price, $measure);

        $this->type = $type;
        $this->quantity = $quantity ?? null;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getType(): InvoiceLineItemType
    {
        return $this->type;
    }

    public function getTotal(): string
    {
        return ($this->quantity ?? 0) * ($this->price ?? 0);
    }

    public function addDiscount($discountAmount): void
    {
        $this->discount += $discountAmount;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }
}