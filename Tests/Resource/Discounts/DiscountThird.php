<?php

namespace Resource\Discounts;


use Wechalet\TaxIdentifier\DiscountIdentifier;
use Wechalet\TaxIdentifier\Enum\IdentifierType;

class DiscountThird extends DiscountIdentifier
{
    protected string $name = 'Discount 3';
    protected float $rate = 300.0;
    protected string $type = IdentifierType::TYPE_FIXED;
}
