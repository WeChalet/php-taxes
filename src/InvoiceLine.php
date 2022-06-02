<?php

namespace Wechalet\TaxIdentifier;

class InvoiceLine
{
    protected ?string $title;
    protected ?float $price;
    protected ?string $measure;

    public function __construct(array $data)
    {
        $this->setTitle($data['title'] ?? null);
        $this->setPrice($data['price'] ?? null);
        $this->setMeasure($data['measure'] ?? null);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice($price): void
    {
        $this->price = $price;
    }

    public function getMeasure(): string
    {
        return $this->measure;
    }

    public function setMeasure($measure): void
    {
        $this->measure = $measure;
    }
}