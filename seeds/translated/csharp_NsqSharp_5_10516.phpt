--TEST--
Translated seed (inferredbugs-csharp: NsqSharp/5)
--FILE--
<?php
$endpoint = 'data://text/plain,' . rawurlencode('<ROOT><PRODUCT>1</PRODUCT><PRODUCT>2147483647</PRODUCT><PRODUCT>-1</PRODUCT></ROOT>');
$response = file_get_contents($endpoint);
$xml = simplexml_load_string($response);
$productIds = [];
foreach ($xml->PRODUCT as $p) {
    $productIds[] = (int)$p;
}

$endpoint2 = 'data://text/plain,' . rawurlencode('<ROOT><ID>42</ID><NAME>Widget</NAME><PRICE>19.95</PRICE></ROOT>');
$response2 = file_get_contents($endpoint2 . '/' . 123);
$xml2 = simplexml_load_string($response2);
$product = [
    'ProductId' => (int)$xml2->ID,
    'Name' => (string)$xml2->NAME,
    'Price' => (float)$xml2->PRICE,
];

echo count($productIds) . "\n";
echo $product['ProductId'] . "\n";
echo $product['Name'] . "\n";
echo $product['Price'] . "\n";
?>
--EXPECT--
