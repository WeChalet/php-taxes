<?php

namespace Wechalet\TaxIdentifier\Actors;


class Buyer extends InvoiceEntity
{
    private string $entity = 'buyer';

    public function __construct(string $name, string $address, string $phone)
    {
        parent::__construct($name, $address, $phone);
    }

    public function getEntity(): string
    {
        return $this->entity;
    }
}