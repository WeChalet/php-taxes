<?php

namespace Wechalet\TaxIdentifier;


use Wechalet\TaxIdentifier\Base\Identifier;
use Wechalet\TaxIdentifier\Base\InvoiceLine;
use Wechalet\TaxIdentifier\Enum\TaxAggregationType;
use Wechalet\TaxIdentifier\Exception\InvalidTaxFormat;
use Wechalet\TaxIdentifier\Interfaces\TaxInterface;
use Wechalet\TaxIdentifier\Types\TaxExemptInvoiceLineItem;

abstract class TaxIdentifier extends Identifier implements TaxInterface
{
    protected string $id;
    protected string $aggregation_type = TaxAggregationType::TAX_AGGREGATION_SUBTOTAL;

    public function __construct(string $id)
    {
        if (!$this->isValidFormat($id))
        {
            throw new InvalidTaxFormat();
        }

        $this->id = $id;
    }

    public function applyTo(Bill $bill): InvoiceLine
    {
        $taxAmount = 0;

        $filtered_taxes = array_filter($bill->taxes, function (InvoiceLineTax $tax){
                return $tax->getTaxIdentifier()->is(TaxAggregationType::TAX_AGGREGATION_TAXED_TOTAL);
            });

        $taxes_label = array_map(function (InvoiceLineTax $item) {
            return $item->getTitle();
        }, $filtered_taxes);

        foreach ($bill->items as $item)
        {
            $taxes = $item->getTaxes($taxes_label);

            $available_tax_amount = count($taxes) > 0 ? $taxes[0]['price'] : 0;

            $taxAmount += $this->applyLine($item, $available_tax_amount);
        }

        return new InvoiceLineTax(
            $this,
            $taxAmount
        );
    }

    public function applyLine(InvoiceLine $item, $tax = 0): float
    {
        if (is_a($item,InvoiceLineItem::class) && is_a($item->getType(), TaxExemptInvoiceLineItem::class)) {
            return 0;
        }

        if (is_a($item,InvoiceLineItem::class)  && !empty($this->applied) && !in_array($item->getTitle() , $this->applied))
            return  0;

        $tax = $this->apply( $item->getTotal() + $tax );

        ray(
            $tax
        );

        $item->addTax(
            array_merge(
                $this->toArray(),
                [
                    'price' => $tax = round($tax,2),
                    //'tax_on' => $item->getTitle()
                ]
            )
        );

        return $tax;
    }

    public function setTaxNotAggregated(): void
    {
        $this->aggregation_type = TaxAggregationType::TAX_AGGREGATION_SUBTOTAL;
    }

    public function setTaxAggregated(): void
    {
        $this->aggregation_type = TaxAggregationType::TAX_AGGREGATION_TAXED_TOTAL;
    }

    public function is(string $aggregation_type): bool
    {
        return $this->aggregation_type == $aggregation_type;
    }
}
