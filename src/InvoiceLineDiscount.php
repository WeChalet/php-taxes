<?php

namespace Wechalet\TaxIdentifier;

use Wechalet\TaxIdentifier\Base\InvoiceLine;

class InvoiceLineDiscount extends InvoiceLine
{
    protected DiscountIdentifier $discountIdentifier;

    public function __construct(DiscountIdentifier $discountIdentifier, float $price)
    {
        parent::__construct($discountIdentifier->getName(), $price);

        $this->discountIdentifier = $discountIdentifier;
    }

    public function getTotal(): string
    {
        return $this->price;
    }

    public function getDiscountIdentifier(): DiscountIdentifier
    {
        return $this->discountIdentifier;
    }
}