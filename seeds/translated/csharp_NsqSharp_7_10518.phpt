--TEST--
Translated seed (inferredbugs-csharp: NsqSharp/7)
--FILE--
<?php
$xml1 = '<ROOT><INVOICE>1</INVOICE><INVOICE>2147483647</INVOICE><INVOICE>-1</INVOICE></ROOT>';
$root = simplexml_load_string($xml1);
$ids = [];
foreach ($root->INVOICE as $n) $ids[] = (int)$n;

$xml2 = '<ROOT><ID>42</ID><CUSTOMERID>7</CUSTOMERID><TOTAL>12345.67</TOTAL></ROOT>';
$root = simplexml_load_string($xml2);
$summary = [
    'InvoiceId' => (int)$root->ID,
    'CustomerId' => (int)$root->CUSTOMERID,
    'Total' => (float)$root->TOTAL,
];

$xml3 = '<ROOT><INVOICEID>10</INVOICEID><ITEM>1</ITEM><PRODUCTID>99</PRODUCTID><QUANTITY>3</QUANTITY><COST>4.50</COST><INVOICEID>11</INVOICEID><ITEM>2</ITEM><PRODUCTID>100</PRODUCTID><QUANTITY>1</QUANTITY><COST>9.99</COST></ROOT>';
$root = simplexml_load_string($xml3);
$details = [];
for ($i = 0; $i < count($root->INVOICEID); $i++) {
    $details[] = [
        'InvoiceId' => (int)$root->INVOICEID[$i],
        'ItemIndex' => (int)$root->ITEM[$i],
        'ProductId' => (int)$root->PRODUCTID[$i],
        'Quantity' => (int)$root->QUANTITY[$i],
        'Cost' => (float)$root->COST[$i],
    ];
}
?>
--EXPECT--
