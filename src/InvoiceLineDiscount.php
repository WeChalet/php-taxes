<?php

namespace Wechalet\TaxIdentifier;

use Wechalet\TaxIdentifier\Base\InvoiceLine;

class InvoiceLineDiscount extends InvoiceLine
{

    public function __construct(string $title, float $price)
    {
        parent::__construct($title, $price);
    }

    public function getTotal(): string
    {
        return $this->price;
    }
}