<?php

namespace Wechalet\TaxIdentifier\Interfaces;

use Wechalet\TaxIdentifier\Base\InvoiceLine;
use Wechalet\TaxIdentifier\Bill;

interface TaxInterface
{
    public function applyTo(Bill $bill): InvoiceLine;
    public function isValidFormat(string $id): bool;
}