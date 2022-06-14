<?php

namespace Wechalet\TaxIdentifier;

use Wechalet\TaxIdentifier\Actors\Biller;
use Wechalet\TaxIdentifier\Actors\Buyer;
use Wechalet\TaxIdentifier\Actors\Seller;

class Bill
{
    private Buyer $buyer;
    private Seller $seller;
    private Biller $biller;

    public array $items = [];
    public array $taxes = [];
    public array $discounts = [];

    public function getSubTotal(): float
    {
        $subtotal = 0.0;

        foreach ($this->items as $item) {
            $subtotal += $item->getTotal();
        }

        return $subtotal;
    }

    public function getTotal(): float
    {
        $total = $this->getSubTotal();

        foreach ($this->biller->getTaxIdentifiers() as $taxIdentifier) {
            $tax = $taxIdentifier->applyTo($this);
            $this->taxes[] = $tax;
            $total += $tax->getPrice();
        }

        foreach ($this->buyer->getDiscountsIdentifiers() as $discountIdentifier) {
            $discount = $discountIdentifier->applyTo($this);
            $this->discounts[] = $discount;
            \Log::info($discount->getPrice());
            $total -= $discount->getPrice();
        }

//        $total -= array_reduce($this->none_deductible_discounts , function (float $acc,InvoiceLine $item){
//            return $acc + $item->getPrice();
//        }, 0.0);

        return $total;
    }

    public function addItem(InvoiceLineItem $item)
    {
        $this->items[] = $item;
    }

    /**
     * @return Buyer
     */
    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    /**
     * @param Buyer $buyer
     */
    public function setBuyer(Buyer $buyer): void
    {
        $this->buyer = $buyer;
    }

    /**
     * @return Seller
     */
    public function getSeller(): Seller
    {
        return $this->seller;
    }

    /**
     * @param Seller $seller
     */
    public function setSeller(Seller $seller): void
    {
        $this->seller = $seller;
    }

    /**
     * @return Biller
     */
    public function getBiller(): Biller
    {
        return $this->biller;
    }

    /**
     * @param Biller $biller
     */
    public function setBiller(Biller $biller): void
    {
        $this->biller = $biller;
    }
}