<?php

namespace Tests\Resource\Taxes;

use Wechalet\TaxIdentifier\Enum\IdentifierType;
use Wechalet\TaxIdentifier\Enum\TaxAggregationType;
use Wechalet\TaxIdentifier\TaxIdentifier;

class TaxForth extends TaxIdentifier
{
    protected string $name = 'QTLT';
    protected float $rate = 3.5;
    protected string $type = IdentifierType::TYPE_RATIO;
    protected string $aggregation_type = TaxAggregationType::TAX_AGGREGATION_TAXED_TOTAL;

    public function isValidFormat(string $id): bool
    {
        return true;
    }
}
