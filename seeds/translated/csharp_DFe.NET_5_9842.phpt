--TEST--
Translated seed (inferredbugs-csharp: DFe.NET/5)
--FILE--
<?php

class Invoice {
    private $items = [];
    private $total = 0;
    private $totalDiscount = 0;

    public function addItem($description, $quantity, $unitPrice, $discount = 0) {
        $this->items[] = [
            'description' => $description,
            'quantity' => $quantity,
            'unitPrice' => $unitPrice,
            'discount' => $discount,
        ];
        $this->total += ($quantity * $unitPrice) - $discount;
        $this->totalDiscount += $discount;
    }

    public function printInvoice() {
        echo "Invoice\n";
        echo str_repeat("-", 20) . "\n";
        foreach ($this->items as $item) {
            echo sprintf("%s: %d @ %.2f - Discount: %.2f\n", 
                $item['description'], 
                $item['quantity'], 
                $item['unitPrice'], 
                $item['discount']);
        }
        echo str_repeat("-", 20) . "\n";
        echo sprintf("Total: %.2f\n", $this->total);
        if ($this->totalDiscount > 0) {
            echo sprintf("Total Discount: %.2f\n", $this->totalDiscount);
        }
        echo str_repeat("-", 20) . "\n";
    }
}

function main() {
    $invoice = new Invoice();
    $invoice->addItem("Item A", 2, 50.00, 5.00); // example of discount
    $invoice->addItem("Item B", 1, 100.00);
    $invoice->addItem("Item C", 3, 20.00, 0.00); // no discount

    $invoice->printInvoice();
}

main();
--EXPECT--
