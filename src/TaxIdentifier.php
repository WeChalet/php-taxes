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

        $collections = [
            $bill->items,
            // check if this tax is applied on items only (not old added taxes)
            $this->aggregation_type == TaxAggregationType::TAX_AGGREGATION_SUBTOTAL ? [] : $bill->taxes
        ];

        foreach ($collections as $collection)
        {
            foreach ($collection as $item)
            {
                $taxAmount += $this->applyLine($item);
            }
        }

        return new InvoiceLineTax(
            $this->getName(),
            $taxAmount
        );
    }

    public function applyLine(InvoiceLine $item): float
    {
        if (is_a($item->getType(), TaxExemptInvoiceLineItem::class)) {
            return 0;
        }

        if (!empty($this->applied) && !in_array($item->getTitle() , $this->applied))
            return  0;

        $item->addTax(
            array_merge(
                $this->toArray(),
                [
                    'price' => $tax = $this->apply( $item->getTotal() )
                ]
            )
        );

        return $tax;
    }
}
