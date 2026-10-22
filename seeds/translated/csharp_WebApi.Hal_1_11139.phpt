--TEST--
Translated seed (inferredbugs-csharp: WebApi.Hal/1)
--FILE--
<?php
class CategoryRepresentation {
    public int $Id;
    public string $Title;
}
class ProductRepresentation {
    public int $Id;
    public string $Title;
    public float $Price;
    public CategoryRepresentation $Category;
}
$representation = new ProductRepresentation();
$representation->Id = 9;
$representation->Title = "Morpheus in a chair statuette";
$representation->Price = 20.14;
$representation->Category = new CategoryRepresentation();
$representation->Category->Id = 99;
$representation->Category->Title = "Action Figures";
echo json_encode($representation, JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION), PHP_EOL;
echo json_encode($representation, JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION | JSON_PRETTY_PRINT), PHP_EOL;
?>
--EXPECT--
