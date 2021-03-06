<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Resource\Discounts\DiscountFirst;
use Resource\Discounts\DiscountSecond;
use Resource\Discounts\DiscountThird;
use Resource\Taxes\TaxFirst;
use Tests\Resource\Taxes\TaxFifth;
use Tests\Resource\Taxes\TaxForth;
use Tests\Resource\Taxes\TaxSecond;
use Tests\Resource\Taxes\TaxSixth;
use Tests\Resource\Taxes\TaxThird;
use Wechalet\TaxIdentifier\Actors\Biller;
use Wechalet\TaxIdentifier\Actors\Buyer;
use Wechalet\TaxIdentifier\Actors\Seller;
use Wechalet\TaxIdentifier\Bill;
use Wechalet\TaxIdentifier\DiscountIdentifier;
use Wechalet\TaxIdentifier\InvoiceLineItem;
use Wechalet\TaxIdentifier\TaxIdentifier;
use Wechalet\TaxIdentifier\Types\TaxableInvoiceLineItem;

class BillTest extends TestCase
{
    public InvoiceLineItem $item_1;
    public InvoiceLineItem $item_2;
    public InvoiceLineItem $item_3;

    public TaxIdentifier $tax_1;
    public TaxIdentifier $tax_2;
    public TaxIdentifier $tax_3;

    public DiscountIdentifier $discount_1;
    public DiscountIdentifier $discount_2;
    public DiscountIdentifier $discount_3;

    public Buyer $buyer;
    public Seller $seller;
    public Biller $Biller;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $taxable_item = new TaxableInvoiceLineItem();

        $this->item_1 = new InvoiceLineItem(
            $taxable_item,
            'Pizza hut margherita 8 slices',
            80.00,
            4,
            "CAD"
        );

        $this->item_2 = new InvoiceLineItem(
            $taxable_item,
            'VW Golf 8 GTI',
            31900.00,
            1,
            "CAD",
        );

        $this->item_3 = new InvoiceLineItem(
            $taxable_item,
            'iPhone 13 pro',
            1300.00,
            2,
            'CAD'
        );

        $this->tax_1 = new TaxFirst("1");
        $this->tax_2 = new TaxSecond("2");
        $this->tax_3 = new TaxThird("3");

        $this->discount_1 = new DiscountFirst();
        $this->discount_2 = new DiscountSecond();
        $this->discount_3 = new DiscountThird();

        $this->buyer = new Buyer('Jane Doe', '123 St., San Francisco, CA, 12345', '8764523111');
        $this->seller = new Seller('Jane Doe', '321 St., New York, NY, 31221', '213123121');
        $this->Biller = new Biller('WeChalet Inc', '54321 Blvd Nature, Montreal, QC, H3TE3K', '4187711211');
    }

    /**
     * @test
     */
    public function shouldCalculateTotalWithTaxDeductible()
    {
        $Bill = new Bill();

        // add taxes
        $this->Biller->addTaxIdentifier($this->tax_1);
        $this->Biller->addTaxIdentifier($this->tax_2);
        $this->Biller->addTaxIdentifier($this->tax_3);

        // add discounts
        $this->buyer->addDiscountsIdentifier($this->discount_1);
        $this->buyer->addDiscountsIdentifier($this->discount_2);
        $this->buyer->addDiscountsIdentifier($this->discount_3);

        // add Bill resources
        $Bill->setBuyer($this->buyer);
        $Bill->setSeller($this->seller);
        $Bill->setBiller($this->Biller);

        // add items to Bill
        $Bill->addItem($this->item_1);
        $Bill->addItem($this->item_2);
        $Bill->addItem($this->item_3);

        // get all Bill items (result)
        $items = $Bill->toArray();

        $this->assertEquals(
            $items,
            [
                "sub_total" => 34820.0,
                "total" => 32877.91,
                "items" => [
                    "Pizza hut margherita 8 slices" => [
                        "label" => "Pizza hut margherita 8 slices",
                        "price" => 80.0,
                        "quantity" => 4,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 320.0,
                        "total" => 163.71,
                        "discount_total" => 404.21,
                        "taxAmount" => 247.92,
                        "discounts" => [
                            [
                                "name" => "Discount 1",
                                "rate" => 10.0,
                                "type" => "RATIO",
                                "price" => 56.79,
                            ],
                            [
                                "name" => "Discount 2",
                                "rate" => 8.35,
                                "type" => "RATIO",
                                "price" => 47.42,
                            ],
                            [
                                "name" => "Discount 3",
                                "rate" => 300.0,
                                "type" => "FIXED",
                                "price" => 300.0,
                            ],
                        ],
                        "taxes" => [
                            [
                                "name" => "Tax 1",
                                "rate" => 5.0,
                                "type" => "RATIO",
                                "price" => 16.0,
                            ],
                            [
                                "name" => "Tax 2",
                                "rate" => 9.975,
                                "type" => "RATIO",
                                "price" => 31.92,
                            ],
                            [
                                "name" => "Tax 3",
                                "rate" => 200.0,
                                "type" => "FIXED",
                                "price" => 200.0,
                            ],
                        ]
                    ],
                    "VW Golf 8 GTI" => [
                        "label" => "VW Golf 8 GTI",
                        "price" => 31900.0,
                        "quantity" => 1,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 31900.0,
                        "total" => 29810.1,
                        "discount_total" => 7066.93,
                        "taxAmount" => 4977.03,
                        "discounts" => [
                            [
                                "name" => "Discount 1",
                                "rate" => 10.0,
                                "type" => "RATIO",
                                "price" => 3687.7,
                            ],
                            [
                                "name" => "Discount 2",
                                "rate" => 8.35,
                                "type" => "RATIO",
                                "price" => 3079.23,
                            ],
                            [
                                "name" => "Discount 3",
                                "rate" => 300.0,
                                "type" => "FIXED",
                                "price" => 300.0,
                            ],
                        ],
                        "taxes" => [
                            [
                                "name" => "Tax 1",
                                "rate" => 5.0,
                                "type" => "RATIO",
                                "price" => 1595.0,
                            ],
                            [
                                "name" => "Tax 2",
                                "rate" => 9.975,
                                "type" => "RATIO",
                                "price" => 3182.03,
                            ],
                            [
                                "name" => "Tax 3",
                                "rate" => 200.0,
                                "type" => "FIXED",
                                "price" => 200.0,
                            ],
                        ]
                    ],
                    "iPhone 13 pro" => [
                        "label" => "iPhone 13 pro",
                        "price" => 1300.0,
                        "quantity" => 2,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 2600.0,
                        "total" => 2304.1,
                        "discount_total" => 885.25,
                        "taxAmount" => 589.35,
                        "discounts" => [
                            [
                                "name" => "Discount 1",
                                "rate" => 10.0,
                                "type" => "RATIO",
                                "price" => 318.94,
                            ],
                            [
                                "name" => "Discount 2",
                                "rate" => 8.35,
                                "type" => "RATIO",
                                "price" => 266.31,
                            ],
                            [
                                "name" => "Discount 3",
                                "rate" => 300.0,
                                "type" => "FIXED",
                                "price" => 300.0,
                            ],
                        ],
                        "taxes" => [
                            [
                                "name" => "Tax 1",
                                "rate" => 5.0,
                                "type" => "RATIO",
                                "price" => 130,
                            ],
                            [
                                "name" => "Tax 2",
                                "rate" => 9.975,
                                "type" => "RATIO",
                                "price" => 259.35,
                            ],
                            [
                                "name" => "Tax 3",
                                "rate" => 200.0,
                                "type" => "FIXED",
                                "price" => 200.0,
                            ],
                        ]
                    ],
                ],
                "taxes" => [
                    "Tax 1" => [
                        "name" => "Tax 1",
                        "rate" => 5.0,
                        "type" => "RATIO",
                        "price" => 1741,
                    ],
                    "Tax 2" => [
                        "name" => "Tax 2",
                        "rate" => 9.975,
                        "type" => "RATIO",
                        "price" => 3473.3,
                    ],
                    "Tax 3" => [
                        "name" => "Tax 3",
                        "rate" => 200.0,
                        "type" => "FIXED",
                        "price" => 600,
                    ],
                ],
                "discounts" => [
                    "Discount 1" => [
                        "name" => "Discount 1",
                        "rate" => 10.0,
                        "type" => "RATIO",
                        "price" => 4063.43,
                    ],
                    "Discount 2" => [
                        "name" => "Discount 2",
                        "rate" => 8.35,
                        "type" => "RATIO",
                        "price" => 3392.96,
                    ],
                    "Discount 3" => [
                        "name" => "Discount 3",
                        "rate" => 300.0,
                        "type" => "FIXED",
                        "price" => 300,
                    ],
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function shouldCalculateTotalWithNoneTaxDeductible()
    {
        $Bill = new Bill();

        // add taxes
        $this->Biller->addTaxIdentifier($this->tax_1);
        $this->Biller->addTaxIdentifier($this->tax_2);
        $this->Biller->addTaxIdentifier($this->tax_3);

        // make discount as none tax deductible
        $this->discount_1->setNoneTaxDeductibleDiscount();
        $this->discount_2->setNoneTaxDeductibleDiscount();
        $this->discount_3->setNoneTaxDeductibleDiscount();

        // add discounts
        $this->buyer->addDiscountsIdentifier($this->discount_1);
        $this->buyer->addDiscountsIdentifier($this->discount_2);
        $this->buyer->addDiscountsIdentifier($this->discount_3);

        // add Bill resources
        $Bill->setBuyer($this->buyer);
        $Bill->setSeller($this->seller);
        $Bill->setBiller($this->Biller);

        // add items to Bill
        $Bill->addItem($this->item_1);
        $Bill->addItem($this->item_2);
        $Bill->addItem($this->item_3);

        // get all Bill items (result)
        $items = $Bill->toArray();

        $this->assertEquals(
            $items,
            [
                "sub_total" => 34820.0,
                "total" => 33944.83,
                "items" => [
                    "Pizza hut margherita 8 slices" => [
                        "label" => "Pizza hut margherita 8 slices",
                        "price" => 80.0,
                        "quantity" => 4,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 320.0,
                        "total" => 209.2,
                        "discount_total" => 358.72,
                        "taxAmount" => 247.92,
                        "discounts" => [
                            [
                                "name" => "Discount 1",
                                "rate" => 10.0,
                                "type" => "RATIO",
                                "price" => 32.0,
                            ],
                            [
                                "name" => "Discount 2",
                                "rate" => 8.35,
                                "type" => "RATIO",
                                "price" => 26.72,
                            ],
                            [
                                "name" => "Discount 3",
                                "rate" => 300.0,
                                "type" => "FIXED",
                                "price" => 300.0,
                            ],
                        ],
                        "taxes" => [
                            [
                                "name" => "Tax 1",
                                "rate" => 5.0,
                                "type" => "RATIO",
                                "price" => 16.0,
                            ],
                            [
                                "name" => "Tax 2",
                                "rate" => 9.975,
                                "type" => "RATIO",
                                "price" => 31.92,
                            ],
                            [
                                "name" => "Tax 3",
                                "rate" => 200.0,
                                "type" => "FIXED",
                                "price" => 200.0,
                            ],
                        ]
                    ],
                    "VW Golf 8 GTI" => [
                        "label" => "VW Golf 8 GTI",
                        "price" => 31900.0,
                        "quantity" => 1,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 31900.0,
                        "total" => 30723.38,
                        "discount_total" => 6153.65,
                        "taxAmount" => 4977.03,
                        "discounts" => [
                            [
                                "name" => "Discount 1",
                                "rate" => 10.0,
                                "type" => "RATIO",
                                "price" => 3190,
                            ],
                            [
                                "name" => "Discount 2",
                                "rate" => 8.35,
                                "type" => "RATIO",
                                "price" => 2663.65,
                            ],
                            [
                                "name" => "Discount 3",
                                "rate" => 300.0,
                                "type" => "FIXED",
                                "price" => 300.0,
                            ],
                        ],
                        "taxes" => [
                            [
                                "name" => "Tax 1",
                                "rate" => 5.0,
                                "type" => "RATIO",
                                "price" => 1595.0,
                            ],
                            [
                                "name" => "Tax 2",
                                "rate" => 9.975,
                                "type" => "RATIO",
                                "price" => 3182.03,
                            ],
                            [
                                "name" => "Tax 3",
                                "rate" => 200.0,
                                "type" => "FIXED",
                                "price" => 200.0,
                            ],
                        ]
                    ],
                    "iPhone 13 pro" => [
                        "label" => "iPhone 13 pro",
                        "price" => 1300.0,
                        "quantity" => 2,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 2600.0,
                        "total" => 2412.25,
                        "discount_total" => 777.1,
                        "taxAmount" => 589.35,
                        "discounts" => [
                            [
                                "name" => "Discount 1",
                                "rate" => 10.0,
                                "type" => "RATIO",
                                "price" => 260,
                            ],
                            [
                                "name" => "Discount 2",
                                "rate" => 8.35,
                                "type" => "RATIO",
                                "price" => 217.1,
                            ],
                            [
                                "name" => "Discount 3",
                                "rate" => 300.0,
                                "type" => "FIXED",
                                "price" => 300.0,
                            ],
                        ],
                        "taxes" => [
                            [
                                "name" => "Tax 1",
                                "rate" => 5.0,
                                "type" => "RATIO",
                                "price" => 130,
                            ],
                            [
                                "name" => "Tax 2",
                                "rate" => 9.975,
                                "type" => "RATIO",
                                "price" => 259.35,
                            ],
                            [
                                "name" => "Tax 3",
                                "rate" => 200.0,
                                "type" => "FIXED",
                                "price" => 200.0,
                            ],
                        ]
                    ],
                ],
                "taxes" => [
                    "Tax 1" => [
                        "name" => "Tax 1",
                        "rate" => 5.0,
                        "type" => "RATIO",
                        "price" => 1741,
                    ],
                    "Tax 2" => [
                        "name" => "Tax 2",
                        "rate" => 9.975,
                        "type" => "RATIO",
                        "price" => 3473.3,
                    ],
                    "Tax 3" => [
                        "name" => "Tax 3",
                        "rate" => 200.0,
                        "type" => "FIXED",
                        "price" => 600,
                    ],
                ],
                "discounts" => [
                    "Discount 1" => [
                        "name" => "Discount 1",
                        "rate" => 10.0,
                        "type" => "RATIO",
                        "price" => 3482,
                    ],
                    "Discount 2" => [
                        "name" => "Discount 2",
                        "rate" => 8.35,
                        "type" => "RATIO",
                        "price" => 2907.47,
                    ],
                    "Discount 3" => [
                        "name" => "Discount 3",
                        "rate" => 300.0,
                        "type" => "FIXED",
                        "price" => 300,
                    ],
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function shouldCalculateTotalWithBothNoneTaxDeductibleAndTaxDeductible()
    {
        $Bill = new Bill();

        // add taxes
        $this->Biller->addTaxIdentifier($this->tax_1);
        $this->Biller->addTaxIdentifier($this->tax_2);
        $this->Biller->addTaxIdentifier($this->tax_3);

        // make discount as none tax deductible
        $this->discount_2->setNoneTaxDeductibleDiscount();
        $this->discount_3->setNoneTaxDeductibleDiscount();

        // add discounts
        $this->buyer->addDiscountsIdentifier($this->discount_1);
        $this->buyer->addDiscountsIdentifier($this->discount_2);
        $this->buyer->addDiscountsIdentifier($this->discount_3);

        // add Bill resources
        $Bill->setBuyer($this->buyer);
        $Bill->setSeller($this->seller);
        $Bill->setBiller($this->Biller);

        // add items to Bill
        $Bill->addItem($this->item_1);
        $Bill->addItem($this->item_2);
        $Bill->addItem($this->item_3);

        // get all Bill items (result)
        $items = $Bill->toArray();

        $this->assertEquals(
            $items,
            [
                "sub_total" => 34820.0,
                "total" => 33363.4,
                "items" => [
                    "Pizza hut margherita 8 slices" => [
                        "label" => "Pizza hut margherita 8 slices",
                        "price" => 80.0,
                        "quantity" => 4,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 320.0,
                        "total" => 184.41,
                        "discount_total" => 383.51,
                        "taxAmount" => 247.92,
                        "discounts" => [
                            [
                                "name" => "Discount 1",
                                "rate" => 10.0,
                                "type" => "RATIO",
                                "price" => 56.79,
                            ],
                            [
                                "name" => "Discount 2",
                                "rate" => 8.35,
                                "type" => "RATIO",
                                "price" => 26.72,
                            ],
                            [
                                "name" => "Discount 3",
                                "rate" => 300.0,
                                "type" => "FIXED",
                                "price" => 300.0,
                            ],
                        ],
                        "taxes" => [
                            [
                                "name" => "Tax 1",
                                "rate" => 5.0,
                                "type" => "RATIO",
                                "price" => 16.0,
                            ],
                            [
                                "name" => "Tax 2",
                                "rate" => 9.975,
                                "type" => "RATIO",
                                "price" => 31.92,
                            ],
                            [
                                "name" => "Tax 3",
                                "rate" => 200.0,
                                "type" => "FIXED",
                                "price" => 200.0,
                            ],
                        ]
                    ],
                    "VW Golf 8 GTI" => [
                        "label" => "VW Golf 8 GTI",
                        "price" => 31900.0,
                        "quantity" => 1,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 31900.0,
                        "total" => 30225.68,
                        "discount_total" => 6651.35,
                        "taxAmount" => 4977.03,
                        "discounts" => [
                            [
                                "name" => "Discount 1",
                                "rate" => 10.0,
                                "type" => "RATIO",
                                "price" => 3687.7,
                            ],
                            [
                                "name" => "Discount 2",
                                "rate" => 8.35,
                                "type" => "RATIO",
                                "price" => 2663.65,
                            ],
                            [
                                "name" => "Discount 3",
                                "rate" => 300.0,
                                "type" => "FIXED",
                                "price" => 300.0,
                            ],
                        ],
                        "taxes" => [
                            [
                                "name" => "Tax 1",
                                "rate" => 5.0,
                                "type" => "RATIO",
                                "price" => 1595.0,
                            ],
                            [
                                "name" => "Tax 2",
                                "rate" => 9.975,
                                "type" => "RATIO",
                                "price" => 3182.03,
                            ],
                            [
                                "name" => "Tax 3",
                                "rate" => 200.0,
                                "type" => "FIXED",
                                "price" => 200.0,
                            ],
                        ]
                    ],
                    "iPhone 13 pro" => [
                        "label" => "iPhone 13 pro",
                        "price" => 1300.0,
                        "quantity" => 2,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 2600.0,
                        "total" => 2353.31,
                        "discount_total" => 836.04,
                        "taxAmount" => 589.35,
                        "discounts" => [
                            [
                                "name" => "Discount 1",
                                "rate" => 10.0,
                                "type" => "RATIO",
                                "price" => 318.94,
                            ],
                            [
                                "name" => "Discount 2",
                                "rate" => 8.35,
                                "type" => "RATIO",
                                "price" => 217.1,
                            ],
                            [
                                "name" => "Discount 3",
                                "rate" => 300.0,
                                "type" => "FIXED",
                                "price" => 300.0,
                            ],
                        ],
                        "taxes" => [
                            [
                                "name" => "Tax 1",
                                "rate" => 5.0,
                                "type" => "RATIO",
                                "price" => 130,
                            ],
                            [
                                "name" => "Tax 2",
                                "rate" => 9.975,
                                "type" => "RATIO",
                                "price" => 259.35,
                            ],
                            [
                                "name" => "Tax 3",
                                "rate" => 200.0,
                                "type" => "FIXED",
                                "price" => 200.0,
                            ],
                        ]
                    ],
                ],
                "taxes" => [
                    "Tax 1" => [
                        "name" => "Tax 1",
                        "rate" => 5.0,
                        "type" => "RATIO",
                        "price" => 1741,
                    ],
                    "Tax 2" => [
                        "name" => "Tax 2",
                        "rate" => 9.975,
                        "type" => "RATIO",
                        "price" => 3473.3,
                    ],
                    "Tax 3" => [
                        "name" => "Tax 3",
                        "rate" => 200.0,
                        "type" => "FIXED",
                        "price" => 600,
                    ],
                ],
                "discounts" => [
                    "Discount 1" => [
                        "name" => "Discount 1",
                        "rate" => 10.0,
                        "type" => "RATIO",
                        "price" => 4063.43,
                    ],
                    "Discount 2" => [
                        "name" => "Discount 2",
                        "rate" => 8.35,
                        "type" => "RATIO",
                        "price" => 2907.47,
                    ],
                    "Discount 3" => [
                        "name" => "Discount 3",
                        "rate" => 300.0,
                        "type" => "FIXED",
                        "price" => 300,
                    ],
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function shouldCalculateTotalWithNoneTaxDeductibleApplyOn()
    {
        $Bill = new Bill();

        // apply discounts on specific items
        $this->tax_1->applyOn(['Pizza hut margherita 8 slices', 'iPhone 13 pro']);
        $this->tax_2->applyOn('VW Golf 8 GTI');

        // add taxes
        $this->Biller->addTaxIdentifier($this->tax_1);
        $this->Biller->addTaxIdentifier($this->tax_2);
        $this->Biller->addTaxIdentifier($this->tax_3);

        // make discount as none tax deductible
        $this->discount_2->setNoneTaxDeductibleDiscount();
        $this->discount_2->applyOn("iPhone 13 pro");

        // add discounts
        $this->buyer->addDiscountsIdentifier($this->discount_2);

        // add Bill resources
        $Bill->setBuyer($this->buyer);
        $Bill->setSeller($this->seller);
        $Bill->setBiller($this->Biller);

        // add items to Bill
        $Bill->addItem($this->item_1);
        $Bill->addItem($this->item_2);
        $Bill->addItem($this->item_3);

        // get all Bill items (result)
        $items = $Bill->toArray();

        $this->assertEquals(
            $items,
            [
                "sub_total" => 34820.0,
                "total" => 38530.93,
                "items" => [
                    "Pizza hut margherita 8 slices" => [
                        "label" => "Pizza hut margherita 8 slices",
                        "price" => 80.0,
                        "quantity" => 4,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 320.0,
                        "total" => 536.0,
                        "discount_total" => 0.0,
                        "taxAmount" => 216.0,
                        "discounts" => [],
                        "taxes" => [
                            [
                                "name" => "Tax 1",
                                "rate" => 5.0,
                                "type" => "RATIO",
                                "price" => 16.0,
                            ],
                            [
                                "name" => "Tax 3",
                                "rate" => 200.0,
                                "type" => "FIXED",
                                "price" => 200.0,
                            ],
                        ]
                    ],
                    "VW Golf 8 GTI" => [
                        "label" => "VW Golf 8 GTI",
                        "price" => 31900.0,
                        "quantity" => 1,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 31900.0,
                        "total" => 35282.03,
                        "discount_total" => 0.0,
                        "taxAmount" => 3382.03,
                        "discounts" => [],
                        "taxes" => [
                            [
                                "name" => "Tax 2",
                                "rate" => 9.975,
                                "type" => "RATIO",
                                "price" => 3182.03,
                            ],
                            [
                                "name" => "Tax 3",
                                "rate" => 200.0,
                                "type" => "FIXED",
                                "price" => 200.0,
                            ],
                        ]
                    ],
                    "iPhone 13 pro" => [
                        "label" => "iPhone 13 pro",
                        "price" => 1300.0,
                        "quantity" => 2,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 2600.0,
                        "total" => 2712.9,
                        "discount_total" => 217.1,
                        "taxAmount" => 330,
                        "discounts" => [
                            [
                                "name" => "Discount 2",
                                "rate" => 8.35,
                                "type" => "RATIO",
                                "price" => 217.1,
                            ]
                        ],
                        "taxes" => [
                            [
                                "name" => "Tax 1",
                                "rate" => 5.0,
                                "type" => "RATIO",
                                "price" => 130,
                            ],
                            [
                                "name" => "Tax 3",
                                "rate" => 200.0,
                                "type" => "FIXED",
                                "price" => 200.0,
                            ],
                        ]
                    ],
                ],
                "taxes" => [
                    "Tax 1" => [
                        "name" => "Tax 1",
                        "rate" => 5.0,
                        "type" => "RATIO",
                        "price" => 146,
                    ],
                    "Tax 2" => [
                        "name" => "Tax 2",
                        "rate" => 9.975,
                        "type" => "RATIO",
                        "price" => 3182.03,
                    ],
                    "Tax 3" => [
                        "name" => "Tax 3",
                        "rate" => 200.0,
                        "type" => "FIXED",
                        "price" => 600,
                    ],
                ],
                "discounts" => [
                    "Discount 2" => [
                        "name" => "Discount 2",
                        "rate" => 8.35,
                        "type" => "RATIO",
                        "price" => 217.1,
                    ]
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function shouldCalculateTotalWithTaxedFixedTotal()
    {
        $Bill = new Bill();

        $this->tax_1->setTaxAggregated();
        // add taxes
        $this->Biller->addTaxIdentifier($this->tax_1);
        $this->Biller->addTaxIdentifier($this->tax_2);
        $this->Biller->addTaxIdentifier($this->tax_3);

        // add Bill resources
        $Bill->setBuyer($this->buyer);
        $Bill->setSeller($this->seller);
        $Bill->setBiller($this->Biller);

        // add items to Bill
        $Bill->addItem($this->item_1);

        // get all Bill items (result)
        $items = $Bill->toArray();

        $this->assertEquals(
            $items,
            [
                "sub_total" => 320.0,
                "total" => 569.52,
                "items" => [
                    "Pizza hut margherita 8 slices" => [
                        "label" => "Pizza hut margherita 8 slices",
                        "price" => 80.0,
                        "quantity" => 4,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 320.0,
                        "total" => 569.52,
                        "discount_total" => 0.0,
                        "taxAmount" => 249.52,
                        "discounts" => [],
                        "taxes" => [
                            [
                                "name" => "Tax 1",
                                "rate" => 5.0,
                                "type" => "RATIO",
                                "price" => 16.0,
                            ],
                            [
                                "name" => "Tax 2",
                                "rate" => 9.975,
                                "type" => "RATIO",
                                "price" => 33.52,
                            ],
                            [
                                "name" => "Tax 3",
                                "rate" => 200.0,
                                "type" => "FIXED",
                                "price" => 200.0,
                            ],
                        ]
                    ],
                ],
                "taxes" => [
                    "Tax 1" => [
                        "name" => "Tax 1",
                        "rate" => 5.0,
                        "type" => "RATIO",
                        "price" => 16.0,
                    ],
                    "Tax 2" => [
                        "name" => "Tax 2",
                        "rate" => 9.975,
                        "type" => "RATIO",
                        "price" => 33.52,
                    ],
                    "Tax 3" => [
                        "name" => "Tax 3",
                        "rate" => 200.0,
                        "type" => "FIXED",
                        "price" => 200,
                    ],
                ],
                "discounts" => []
            ]
        );
    }

    /**
     * @test
     */
    public function shouldCalculateTotalWithTaxedFixedTotalSecond()
    {
        $Bill = new Bill();

        // add taxes
        $tax_1 = new TaxForth('3');
        $tax_2 = new TaxFifth('2');
        $tax_3 = new TaxSixth('1');

        $this->Biller->addTaxIdentifier($tax_1);
        $this->Biller->addTaxIdentifier($tax_2);
        $this->Biller->addTaxIdentifier($tax_3);

        // add Bill resources
        $Bill->setBuyer($this->buyer);
        $Bill->setSeller($this->seller);
        $Bill->setBiller($this->Biller);

        // add items to Bill
        $item = new InvoiceLineItem(
            new TaxableInvoiceLineItem(),
            'Item 1',
            170,
            4,
            "CAD"
        );

        $item_2 = new InvoiceLineItem(
            new TaxableInvoiceLineItem(),
            'Item 2',
            60,
            2,
            "CAD"
        );

        $Bill->addItem($item);
        $Bill->addItem($item_2);

        // get all Bill items (result)
        $items = $Bill->toArray();

        $this->assertEquals(
            $items,
            [
                'sub_total' => 800.0,
                'total' => 951.99,
                "items" => [
                    "Item 1" => [
                        "label" => "Item 1",
                        "price" => 170.0,
                        "quantity" => 4,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 680,
                        "total" => 809.19,
                        "discount_total" => 0.0,
                        "taxAmount" => 129.19,
                        "discounts" => [],
                        "taxes" => [
                            [
                                "name" => "QTLT",
                                "rate" => 3.5,
                                "type" => "RATIO",
                                "price" => 23.8,
                            ],
                            [
                                "name" => "GST",
                                "rate" => 5,
                                "type" => "RATIO",
                                "price" => 35.19,
                            ],
                            [
                                "name" => "QST",
                                "rate" => 9.975,
                                "type" => "RATIO",
                                "price" => 70.20,
                            ],
                        ]
                    ],
                    "Item 2" => [
                        "label" => "Item 2",
                        "price" => 60.0,
                        "quantity" => 2,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 120.0,
                        'total' => 142.8,
                        'discount_total' => 0.0,
                        'taxAmount' => 22.8,
                        "discounts" => [],
                        "taxes" => [
                            [
                                'name' => 'QTLT',
                                'rate' => 3.5,
                                'type' => 'RATIO',
                                'price' => 4.2,
                            ],
                            [
                                'name' => 'GST',
                                'rate' => 5.0,
                                'type' => 'RATIO',
                                'price' => 6.21,
                            ],
                            [
                                'name' => 'QST',
                                'rate' => 9.975,
                                'type' => 'RATIO',
                                'price' => 12.39,
                            ]
                        ]
                    ],
                ],
                "taxes" => [
                    'QTLT' => [
                        'name' => 'QTLT',
                        'rate' => 3.5,
                        'type' => 'RATIO',
                        'price' => 28.0,
                    ],
                    'GST' => [
                        'name' => 'GST',
                        'rate' => 5.0,
                        'type' => 'RATIO',
                        'price' => 41.4,
                    ],
                    'QST' => [
                        'name' => 'QST',
                        'rate' => 9.975,
                        'type' => 'RATIO',
                        'price' => 82.59
                    ]
                ],
                "discounts" => []
            ]
        );
    }

    /**
     * @test
     */
    public function shouldCalculateTotalWithTaxedFixedTotalWithDiscount()
    {
        $Bill = new Bill();

        // add taxes
        $tax_1 = new TaxForth('3');
        $tax_2 = new TaxFifth('2');
        $tax_3 = new TaxSixth('1');

        $this->Biller->addTaxIdentifier($tax_1);
        $this->Biller->addTaxIdentifier($tax_2);
        $this->Biller->addTaxIdentifier($tax_3);

        // add discounts
        $this->buyer->addDiscountsIdentifier($this->discount_1);

        // add Bill resources
        $Bill->setBuyer($this->buyer);
        $Bill->setSeller($this->seller);
        $Bill->setBiller($this->Biller);

        // add items to Bill
        $item = new InvoiceLineItem(
            new TaxableInvoiceLineItem(),
            'Item 1',
            170,
            4,
            "CAD"
        );

        $item_2 = new InvoiceLineItem(
            new TaxableInvoiceLineItem(),
            'Item 2',
            60,
            2,
            "CAD"
        );

        $Bill->addItem($item);
        $Bill->addItem($item_2);

        // get all Bill items (result)
        $items = $Bill->toArray();

        $this->assertEquals(
            $items,
            [
                'sub_total' => 800.0,
                'total' => 951.99,
                "items" => [
                    "Item 1" => [
                        "label" => "Item 1",
                        "price" => 170.0,
                        "quantity" => 4,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 680,
                        "total" => 809.19,
                        "discount_total" => 0.0,
                        "taxAmount" => 129.19,
                        "discounts" => [],
                        "taxes" => [
                            [
                                "name" => "QTLT",
                                "rate" => 3.5,
                                "type" => "RATIO",
                                "price" => 23.8,
                            ],
                            [
                                "name" => "GST",
                                "rate" => 5,
                                "type" => "RATIO",
                                "price" => 35.19,
                            ],
                            [
                                "name" => "QST",
                                "rate" => 9.975,
                                "type" => "RATIO",
                                "price" => 70.20,
                            ],
                        ]
                    ],
                    "Item 2" => [
                        "label" => "Item 2",
                        "price" => 60.0,
                        "quantity" => 2,
                        "measure" => "CAD",
                        "type" => "TaxableInvoiceLineItem",
                        "sub_total" => 120.0,
                        'total' => 142.8,
                        'discount_total' => 0.0,
                        'taxAmount' => 22.8,
                        "discounts" => [],
                        "taxes" => [
                            [
                                'name' => 'QTLT',
                                'rate' => 3.5,
                                'type' => 'RATIO',
                                'price' => 4.2,
                            ],
                            [
                                'name' => 'GST',
                                'rate' => 5.0,
                                'type' => 'RATIO',
                                'price' => 6.21,
                            ],
                            [
                                'name' => 'QST',
                                'rate' => 9.975,
                                'type' => 'RATIO',
                                'price' => 12.39,
                            ]
                        ]
                    ],
                ],
                "taxes" => [
                    'QTLT' => [
                        'name' => 'QTLT',
                        'rate' => 3.5,
                        'type' => 'RATIO',
                        'price' => 28.0,
                    ],
                    'GST' => [
                        'name' => 'GST',
                        'rate' => 5.0,
                        'type' => 'RATIO',
                        'price' => 41.4,
                    ],
                    'QST' => [
                        'name' => 'QST',
                        'rate' => 9.975,
                        'type' => 'RATIO',
                        'price' => 82.59
                    ]
                ],
                "discounts" => []
            ]
        );
    }
}
