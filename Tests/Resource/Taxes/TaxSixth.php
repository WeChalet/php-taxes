<?php

namespace Tests\Resource\Taxes;

use Wechalet\TaxIdentifier\Enum\IdentifierType;
use Wechalet\TaxIdentifier\Enum\TaxAggregationType;
use Wechalet\TaxIdentifier\TaxIdentifier;

class TaxSixth extends TaxIdentifier
{
    protected string $name = 'QST';
    protected float $rate = 9.975;
    protected string $type = IdentifierType::TYPE_RATIO;

    public function isValidFormat(string $id): bool
    {
        return true;
    }
}
