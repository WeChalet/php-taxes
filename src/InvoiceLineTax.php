<?php

namespace Wechalet\TaxIdentifier;

use Wechalet\TaxIdentifier\Base\InvoiceLine;

class InvoiceLineTax extends InvoiceLine
{
    protected TaxIdentifier $taxIdentifier;

    public function __construct(TaxIdentifier $taxIdentifier, float $price )
    {
        parent::__construct($taxIdentifier->getName(), $price);

        $this->taxIdentifier = $taxIdentifier;
    }

    public function getTotal(): string
    {
        return round($this->price, 2);
    }

    public function getTaxIdentifier(): TaxIdentifier
    {
        return $this->taxIdentifier;
    }
}