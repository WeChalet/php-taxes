<?php

namespace Wechalet\TaxIdentifier\Base;

class InvoiceLine
{
    protected ?string $title;
    protected ?float $price = 0.0;
    protected ?string $measure = "CAD";
    // tax
    protected array $taxes = [];
    // discount
    protected array $discounts = [];

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

    public function addTax($tax): void
    {
        $this->taxes[] = $tax;
    }

    public function getTax(): float
    {
        return array_sum( array_column($this->taxes, 'amount') );
    }

    public function addDiscount($discount): void
    {
        $this->discounts[] = $discount;
    }

    public function getDiscount(): float
    {
        return array_sum( array_column($this->discounts, 'amount') );
    }
}