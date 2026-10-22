--TEST--
Translated seed (inferredbugs-csharp: NsqSharp/6)
--FILE--
<?php
$xml = '<ROOT><INVOICE>1</INVOICE><INVOICE>2147483647</INVOICE><INVOICE>-1</INVOICE></ROOT>';
$doc = new SimpleXMLElement($xml);
$ids = [];
foreach ($doc->INVOICE as $n) {
    $ids[] = (int)$n;
}

$xml = '<ROOT><ID>42</ID><CUSTOMERID>2147483647</CUSTOMERID><TOTAL>12345.6789</TOTAL></ROOT>';
$doc = new SimpleXMLElement($xml);
$summary = [
    'InvoiceId' => (int)$doc->ID,
    'CustomerId' => (int)$doc->CUSTOMERID,
    'Total' => (float)$doc->TOTAL,
];

$xml = '<ROOT><INVOICEID>10</INVOICEID><ITEM>1</ITEM><PRODUCTID>100</PRODUCTID><QUANTITY>2</QUANTITY><COST>9.99</COST><INVOICEID>11</INVOICEID><ITEM>3</ITEM><PRODUCTID>200</PRODUCTID><QUANTITY>4</QUANTITY><COST>19.95</COST></ROOT>';
$doc = new SimpleXMLElement($xml);
$details = [];
foreach ($doc->INVOICEID as $lineItem) {
    $detail = ['InvoiceId' => (int)$lineItem];
    $node = dom_import_simplexml($lineItem)->nextSibling;
    while ($node && $node->nodeType === XML_ELEMENT_NODE && $node->nodeName !== 'INVOICEID') {
        $name = $node->nodeName;
        $value = (string)$node->textContent;
        if ($name === 'ITEM') $detail['ItemIndex'] = (int)$value;
        elseif ($name === 'PRODUCTID') $detail['ProductId'] = (int)$value;
        elseif ($name === 'QUANTITY') $detail['Quantity'] = (int)$value;
        elseif ($name === 'COST') $detail['Cost'] = (float)$value;
        $node = $node->nextSibling;
    }
    $details[] = $detail;
}
?>
--EXPECT--
