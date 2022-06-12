<?php

namespace Wechalet\TaxIdentifier\Actors;

class Seller extends InvoiceEntity
{
    private string $entity = 'seller';

    public function getEntity(): string
    {
        return $this->entity;
    }
}