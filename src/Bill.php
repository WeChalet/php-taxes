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

        return round($subtotal, 2);
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
            $total -= $discount->getPrice();
        }

        return round($total, 2);
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

    public function toArray(): array
    {
        ;

        return [
            'sub_total' => $this->getSubTotal(),
            'total' => $this->getTotal(),
            // items
            'items' => array_map(function (InvoiceLineItem $item) {
                return [
                    "title"=> $item->getTitle(),
                    "price"=> $item->getPrice(),
                    "quantity"=> $item->getQuantity(),
                    "measure"=> $item->getMeasure(),
                    "type"=> $item->getType()->getClassName(),
                    "total"=> $item->getQuantity() * $item->getPrice(),
                    "discount_total"=> $item->getDiscount(),
                    "taxAmount"=> $item->getTax(),
                    "discounts"=> $item->getDiscounts(),
                    "taxes"=> $item->getTaxes(),
                ];

            }, $this->items),

            'taxes' => array_map(function (InvoiceLineTax $tax) {
                return $tax->getTaxIdentifier()->toArray() + [
                    "price"=> $tax->getTotal()
                ];

            }, $this->taxes),

            'discounts' => array_map(function (InvoiceLineDiscount $discount) {
                return $discount->getDiscountIdentifier()->toArray() + [
                        "price"=> $discount->getTotal()
                    ];

            }, $this->discounts)
        ];
    }
}