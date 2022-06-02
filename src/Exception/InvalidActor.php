<?php

namespace Wechalet\TaxIdentifier\Exception;

class InvalidActor extends \Exception
{
    public function errorMessage(): string
    {
        return 'Can only set the "Buyer", "Seller" and "Biller".';
    }
}