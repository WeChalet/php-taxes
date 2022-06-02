<?php

namespace Wechalet\TaxIdentifier\Exception;

class InvalidTaxRate extends \Exception
{
    public function errorMessage(): string
    {
        return 'Rate can not be greater than 100 when type is a ratio.';
    }
}