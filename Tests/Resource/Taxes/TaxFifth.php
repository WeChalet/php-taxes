<?php

namespace Tests\Resource\Taxes;

use Wechalet\TaxIdentifier\Enum\IdentifierType;
use Wechalet\TaxIdentifier\Enum\TaxAggregationType;
use Wechalet\TaxIdentifier\TaxIdentifier;

class TaxFifth extends TaxIdentifier
{
    protected string $name = 'GST';
    protected float $rate = 5;
    protected string $type = IdentifierType::TYPE_RATIO;

    public function isValidFormat(string $id): bool
    {
        return true;
    }
}
