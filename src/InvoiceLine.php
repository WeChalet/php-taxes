<?php

namespace Wechalet\TaxIdentifier;

class InvoiceLine
{
    protected ?string $title;
    protected ?float $price;
    protected ?string $measure;

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
}