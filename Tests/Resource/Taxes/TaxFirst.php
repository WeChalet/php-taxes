<?php

namespace Resource\Taxes;

use Wechalet\TaxIdentifier\Enum\IdentifierType;
use Wechalet\TaxIdentifier\TaxIdentifier;

class TaxFirst extends TaxIdentifier
{
    protected string $name = 'Tax 1';
    protected float $rate = 5.0;
    protected string $type = IdentifierType::TYPE_RATIO;

    public function isValidFormat(string $id): bool
    {
        return true;
    }
}
