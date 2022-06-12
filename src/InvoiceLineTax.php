<?php

namespace Wechalet\TaxIdentifier;

class InvoiceLineTax extends InvoiceLine
{

    public function __construct(string $title, float $price)
    {
        parent::__construct($title, $price);
    }
}