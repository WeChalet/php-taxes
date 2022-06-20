<?php

namespace Wechalet\TaxIdentifier\Types;

class InvoiceLineItemType
{
    public function getClassName(): string
    {
        $class = explode("\\",static::class);
        return $class[count($class) - 1];
    }
}