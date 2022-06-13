<?php

namespace Wechalet\TaxIdentifier\Discount;

use Wechalet\TaxIdentifier\InvoiceLine;

class InvoiceLineTaxDeductibleDiscount extends InvoiceLine
{
    public function __construct(string $title, float $price)
    {
        parent::__construct($title, $price);
    }
}