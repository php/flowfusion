--TEST--
Translated seed (inferredbugs-csharp: ExcelMapper/4)
--FILE--
<?php

class Product {
    public $Name;
    public $NumberInStock;
    public $Price;
    public $Value;

    public function equals($obj) {
        if (!($obj instanceof Product)) return false;
        return $obj->Name === $this->Name && $obj->NumberInStock === $this->NumberInStock && 
               $obj->Price === $this->Price && $obj->Value === $this->Value;
    }

    public function getHashCode() {
        return crc32($this->Name . $this->NumberInStock . $this->Price . $this->Value);
    }
}

function fetchProducts() {
    return [
        (new Product())->setProperties("Nudossi", 60, 1.99, "C2*D2"),
        (new Product())->setProperties("Halloren", 33, 2.99, "C3*D3"),
        (new Product())->setProperties("Filinchen", 100, 0.99, "C5*D5"),
    ];
}

function main() {
    $products = fetchProducts();
    
    foreach ($products as $product) {
        echo "Name: {$product->Name}, Stock: {$product->NumberInStock}, Price: {$product->Price}, Value: {$product->Value}\n";
    }
}

main();
--EXPECT--
