<?php

namespace Wechalet\TaxIdentifier;

use Wechalet\TaxIdentifier\Base\InvoiceLine;
use Wechalet\TaxIdentifier\Types\InvoiceLineItemType;
use Wechalet\TaxIdentifier\Types\TaxableInvoiceLineItem;

class InvoiceLineItem extends InvoiceLine
{
    protected InvoiceLineItemType $type;
    protected int $quantity = 1;

    /**
     * @throws \Exception
     */
    public function __construct(InvoiceLineItemType $type, string $title, float $price, int $quantity, ?string $measure)
    {
        parent::__construct($title, $price, $measure);

        $this->type = $type;
        $this->quantity = $quantity ?? 1;

        if($this->quantity < 1){
            throw new \Exception('Invalid quantity parameter value');
        }
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getType(): InvoiceLineItemType
    {
        return $this->type;
    }

    public function getTotal(): string
    {
        return ($this->quantity ?? 0) * ($this->price ?? 0);
    }
}