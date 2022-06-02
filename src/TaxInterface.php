<?php

namespace Wechalet\TaxIdentifier;

interface TaxInterface
{
    public function applyTo(Bill $bill): InvoiceLine;
    public function isValidFormat(string $id): bool;
}