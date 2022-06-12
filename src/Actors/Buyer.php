<?php

namespace Wechalet\TaxIdentifier\Actors;


class Buyer extends InvoiceEntity
{
    private string $entity = 'buyer';

    public function getEntity(): string
    {
        return $this->entity;
    }
}