--TEST--
Translated seed (inferredbugs-csharp: ExcelMapper/3)
--FILE--
<?php

class Product
{
    public $name;
    public $numberInStock;
    public $price;
    public $value;

    public function __construct($name, $numberInStock, $price, $value)
    {
        $this->name = $name;
        $this->numberInStock = $numberInStock;
        $this->price = $price;
        $this->value = $value;
    }

    public function equals($obj)
    {
        if (!($obj instanceof Product)) return false;
        return $this->name === $obj->name && 
               $this->numberInStock === $obj->numberInStock && 
               $this->price === $obj->price && 
               $this->value === $obj->value;
    }
}

function fetchProducts()
{
    return [
        new Product("Nudossi", 60, 1.99, "C2*D2"),
        new Product("Halloren", 33, 2.99, "C3*D3"),
        new Product("Filinchen", 100, 0.99, "C5*D5"),
    ];
}

function simulateFetchTest()
{
    $products = fetchProducts();
    foreach ($products as $product) {
        echo $product->name . " - " . $product->numberInStock . " - " . 
             $product->price . " - " . $product->value . PHP_EOL;
    }
}

simulateFetchTest();
--EXPECT--
