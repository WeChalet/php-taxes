<?php

namespace Wechalet\TaxIdentifier\Exception;

class InvalidTaxType extends \Exception
{
    public function errorMessage(): string
    {
        return 'Unsupported tax type.';
    }
}