--TEST--
Translated seed (inferredbugs-csharp: Transformalize/1)
--FILE--
<?php
$xml = '<root><a>1</a><a>2</a><b>true</b><c>3.14</c><d>2024-01-02T03:04:05Z</d><e>text</e><!--x--><f>ignored</f></root>';
$doc = new DOMDocument();
$doc->preserveWhiteSpace = false;
$doc->loadXML($xml, LIBXML_NOBLANKS);

$row = ['xml' => $xml];
$map = ['a' => 'a1', 'b' => 'b1', 'c' => 'c1', 'd' => 'd1', 'e' => 'e1'];
$typeMap = ['a' => 'int', 'b' => 'bool', 'c' => 'float', 'd' => 'datetime', 'e' => 'string'];

foreach ($doc->documentElement->childNodes as $node) {
    if ($node->nodeType !== XML_ELEMENT_NODE) continue;
    $name = $node->nodeName;
    while (isset($map[$name])) {
        $value = $node->textContent;
        switch ($typeMap[$name]) {
            case 'int':
                $row[$map[$name]] = (int)$value;
                break;
            case 'bool':
                $row[$map[$name]] = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                break;
            case 'float':
                $row[$map[$name]] = (float)$value;
                break;
            case 'datetime':
                $row[$map[$name]] = new DateTime($value);
                break;
            default:
                $row[$map[$name]] = $value;
                break;
        }
        break;
    }
}

echo "ok\n";
--EXPECT--
