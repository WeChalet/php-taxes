<?php

namespace Wechalet\TaxIdentifier;

class InvoiceLine
{
    protected ?string $title;
    protected ?float $price = 0.0;
    protected ?string $measure = "CAD";
    protected float $taxAmount = 0.0;

    public function __construct(string $title, float $price, ?string $measure = null)
    {
        $this->title = $title ?? null;
        $this->price = $price ?? null;
        $this->measure = $measure ?? null;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getMeasure(): string
    {
        return $this->measure;
    }

    public function addTax($taxAmount): void
    {
        $this->taxAmount += $taxAmount;
    }

    public function getTax(): float
    {
        return $this->taxAmount;
    }
}