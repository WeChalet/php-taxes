<?php

namespace Wechalet\TaxIdentifier\Exception;

class InvalidTaxFormat extends \Exception
{
    public function errorMessage(): string
    {
        return 'The ID you entered is invalid.';
    }
}