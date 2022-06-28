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
        $this->taxes = [];
        $this->discounts = [];
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
        return [
            'sub_total' => $this->getSubTotal(),
            'total' => $this->getTotal(),
            // items
            'items' => array_reduce($this->items, function (array $acc, InvoiceLineItem $item) {
                $acc [$item->getTitle()] = [
                    "label"=> $item->getTitle(),
                    "price"=> $item->getPrice(),
                    "quantity"=> $item->getQuantity(),
                    "measure"=> $item->getMeasure(),
                    "type"=> $item->getType()->getClassName(),
                    "sub_total"=> $sub_total = round($item->getQuantity() * $item->getPrice(), 2),
                    "total"=> round(($sub_total) + ($item->getTax() - $item->getDiscount()) , 2),
                    "discount_total"=> $item->getDiscount(),
                    "taxAmount"=> $item->getTax(),
                    "discounts"=> $item->getDiscounts(),
                    "taxes"=> $item->getTaxes(),
                ];

                return $acc;
            }, []),

            'taxes' => array_reduce($this->taxes, function (array $acc, InvoiceLineTax $tax) {
                $acc[$tax->getTitle()] = $tax->getTaxIdentifier()->toArray() + [
                    "price"=> $tax->getTotal()
                ];

                return $acc;
            }, []),

            'discounts' => array_reduce($this->discounts, function (array $acc, InvoiceLineDiscount $discount) {
                $acc[$discount->getTitle()] = $discount->getDiscountIdentifier()->toArray() + [
                        "price"=> $discount->getTotal()
                    ];

                return $acc;
            }, [])
        ];
    }
}