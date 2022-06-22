<?php

namespace Tests\Resource\Taxes;

use Wechalet\TaxIdentifier\Enum\IdentifierType;
use Wechalet\TaxIdentifier\TaxIdentifier;

class TaxThird extends TaxIdentifier
{
    protected string $name = 'Tax 3';
    protected float $rate = 200.0;
    protected string $type = IdentifierType::TYPE_FIXED;

    public function isValidFormat(string $id): bool
    {
        return true;
    }
}
