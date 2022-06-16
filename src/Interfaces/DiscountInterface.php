<?php

namespace Wechalet\TaxIdentifier\Interfaces;

use Wechalet\TaxIdentifier\Base\InvoiceLine;
use Wechalet\TaxIdentifier\Bill;

interface DiscountInterface
{
    public function applyTo(Bill $bill): InvoiceLine;
}