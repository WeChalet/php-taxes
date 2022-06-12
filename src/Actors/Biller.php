<?php

namespace Wechalet\TaxIdentifier\Actors;

use Wechalet\TaxIdentifier\TaxIdentifier;

class Biller extends InvoiceEntity
{
    private string $entity = 'biller';
    private array $taxIdentifiers = [];

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function addTaxIdentifier(TaxIdentifier $taxIdentifier)
    {
        array_push($this->taxIdentifiers, $taxIdentifier);
    }

    /**
     * @return array
     */
    public function getTaxIdentifiers(): array
    {
        return $this->taxIdentifiers;
    }
}