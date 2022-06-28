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
        $tax = array_sum( array_column($this->taxes, 'price') );
        return round($tax, 2);
    }

    public function getTaxes($key = null): array
    {
        if(is_array($key)){
            return array_filter($this->taxes, function ($item) use ($key){
               return in_array($item['name'], $key);
            });
        }

        if(!empty($key) && in_array($key, $this->taxes)){
            return $this->taxes[$key];
        }

        return $this->taxes;
    }

    public function addDiscount($discount): void
    {
        $this->discounts[] = $discount;
    }

    public function getDiscount(): float
    {
        $discount = array_sum( array_column($this->discounts, 'price') );
        return round($discount, 2);
    }

    public function getDiscounts(?string $key = null): array
    {
        if(!empty($key) && in_array($key, $this->discounts)){
            return $this->discounts[$key];
        }

        return $this->discounts;
    }
}