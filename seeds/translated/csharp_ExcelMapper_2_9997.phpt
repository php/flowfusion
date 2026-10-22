--TEST--
Translated seed (inferredbugs-csharp: ExcelMapper/2)
--FILE--
<?php

class Product {
    public $Name;
    public $NumberInStock;
    public $Price;
    public $Value;

    public function equals($obj) {
        if (!($obj instanceof Product)) return false;
        return $obj->Name === $this->Name && 
               $obj->NumberInStock === $this->NumberInStock && 
               $obj->Price === $this->Price && 
               $obj->Value === $this->Value;
    }

    public function getHashCode() {
        return crc32($this->Name . $this->NumberInStock . $this->Price . $this->Value);
    }
}

class ExcelMapper {
    private $filePath;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function fetch() {
        // Simulating fetching data
        return [
            (new Product())->setAttributes("Nudossi", 60, 1.99, "C2*D2"),
            (new Product())->setAttributes("Halloren", 33, 2.99, "C3*D3"),
            (new Product())->setAttributes("Filinchen", 100, 0.99, "C5*D5"),
        ];
    }

    public function save($file, $products) {
        // Simulating saving to Excel
        // Logic to write $products to an Excel file would go here
    }
}

$productList = (new ExcelMapper("products.xlsx"))->fetch();

$savedFile = "products_save.xlsx";
(new ExcelMapper($savedFile))->save($savedFile, $productList);

class ProductValue {
    public $Value;
}

class ProductValueString {
    public $Value;
    public $ValueDefaultAsFormula;
    public $ValueAsString;
}

function runTest() {
    $products = (new ExcelMapper("products.xlsx"))->fetch();
    foreach ($products as $product) {
        echo $product->Name . "\n";
    }
}

runTest();
--EXPECT--
