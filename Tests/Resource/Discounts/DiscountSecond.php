<?php

namespace Resource\Discounts;


use Wechalet\TaxIdentifier\DiscountIdentifier;
use Wechalet\TaxIdentifier\Enum\IdentifierType;

class DiscountSecond extends DiscountIdentifier
{
    protected string $name = 'Discount 2';
    protected float $rate = 8.35;
    protected string $type = IdentifierType::TYPE_RATIO;
}
