<?php

namespace Tests\Resource\Taxes;

use Wechalet\TaxIdentifier\Enum\IdentifierType;
use Wechalet\TaxIdentifier\TaxIdentifier;

class TaxSecond extends TaxIdentifier
{
    protected string $name = 'Tax 2';
    protected float $rate = 9.975;
    protected string $type = IdentifierType::TYPE_RATIO;

    public function isValidFormat(string $id): bool
    {
        return true;
    }
}
