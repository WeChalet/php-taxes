<?php

namespace Wechalet\TaxIdentifier\Exception;

class InvalidEntityType extends \Exception
{
    public function errorMessage(): string
    {
        return 'Expecting an InvoiceEntity type of object.';
    }
}