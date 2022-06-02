<?php

namespace Wechalet\TaxIdentifier;

use Wechalet\TaxIdentifier\Types\InvoiceLineItemType;

class InvoiceLineItem extends InvoiceLine
{
    protected InvoiceLineItemType $type;
    protected ?int $quantity;

    public function __construct(InvoiceLineItemType $type, array $data)
    {
        parent::__construct($data);

        $this->type = $type;
        $this->setQuantity($data['quantity'] ?? null);
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getType(): InvoiceLineItemType
    {
        return $this->type;
    }

    public function setType(InvoiceLineItemType $type): void
    {
        $this->type = $type;
    }

    public function getTotal(): string
    {
        return ($this->quantity ?? 0) * ($this->price ?? 0);
    }
}