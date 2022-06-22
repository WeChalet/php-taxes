<?php

namespace Resource\Discounts;


use Wechalet\TaxIdentifier\DiscountIdentifier;
use Wechalet\TaxIdentifier\Enum\IdentifierType;

class DiscountFirst extends DiscountIdentifier
{
    protected string $name = 'Discount 1';
    protected float $rate = 10;
    protected string $type = IdentifierType::TYPE_RATIO;
}
