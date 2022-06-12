<?php

namespace Wechalet\TaxIdentifier\Actors;

abstract class InvoiceEntity
{
    protected string $name;
    protected  string $address;
    protected  string $phone;

    public function __construct(string $name, string $address, string $phone)
    {
        $this->name = $name;
        $this->address = $address;
        $this->phone = $phone;
    }

    /**
     * @param string $address
     * @return InvoiceEntity
     */
    public function setAddress(string $address): InvoiceEntity
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $name
     * @return InvoiceEntity
     */
    public function setName(string $name):  InvoiceEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $phone
     * @return InvoiceEntity
     */
    public function setPhone(string $phone): InvoiceEntity
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }
}