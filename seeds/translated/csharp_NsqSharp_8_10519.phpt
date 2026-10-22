--TEST--
Translated seed (inferredbugs-csharp: NsqSharp/8)
--FILE--
<?php
$xml1 = '<ROOT><CUSTOMER>1</CUSTOMER><CUSTOMER>2147483647</CUSTOMER><CUSTOMER>-2147483648</CUSTOMER><CUSTOMER>00042</CUSTOMER></ROOT>';
$doc1 = new SimpleXMLElement($xml1);
$ids = [];
foreach ($doc1->CUSTOMER as $c) {
    $ids[] = (int)$c;
}

$xml2 = '<ROOT><ID>123</ID><FIRSTNAME>John</FIRSTNAME><LASTNAME>Doe</LASTNAME><STREET>Main</STREET><CITY>Metropolis</CITY></ROOT>';
$doc2 = new SimpleXMLElement($xml2);
$customer = [
    'CustomerId' => (int)$doc2->ID,
    'FirstName' => (string)$doc2->FIRSTNAME,
    'LastName' => (string)$doc2->LASTNAME,
    'Street' => (string)$doc2->STREET,
    'City' => (string)$doc2->CITY,
];

$xml3 = '<ROOT><CUSTOMER>0</CUSTOMER><CUSTOMER>01</CUSTOMER><CUSTOMER>000</CUSTOMER></ROOT>';
$doc3 = new SimpleXMLElement($xml3);
foreach ($doc3->CUSTOMER as $c) {
    $ids[] = (int)$c;
}

echo count($ids) . PHP_EOL;
echo $customer['CustomerId'] . PHP_EOL;
echo implode(',', $ids) . PHP_EOL;
?>
--EXPECT--
