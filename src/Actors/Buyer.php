<?php

namespace Wechalet\TaxIdentifier\Actors;


use Wechalet\TaxIdentifier\DiscountIdentifier;
use Wechalet\TaxIdentifier\TaxIdentifier;

class Buyer extends InvoiceEntity
{
    private string $entity = 'buyer';
    private array $discountIdentifiers = [];

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function addDiscountsIdentifier(DiscountIdentifier $discountIdentifier)
    {
        array_push($this->discountIdentifiers, $discountIdentifier);
    }

    public function getDiscountsIdentifiers(): array
    {
        return $this->discountIdentifiers;
    }
}