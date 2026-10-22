--TEST--
Translated seed (inferredbugs-csharp: Threenine.Data/2)
--FILE--
<?php
class TestProduct {
    public int $Id = 0;
    public string $Name = '';
}

$store = [];
$newProduct = new TestProduct();
$newProduct->Name = 'Test Product';

$store[] = $newProduct;
$newProduct->Id = count($store);

echo $newProduct->Id;
?>
--EXPECT--
