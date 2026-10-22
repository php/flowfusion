--TEST--
Translated seed (inferredbugs-csharp: OPC-UA-OOI/1)
--FILE--
<?php
class Item {
    public $name = '';
    public $value = 0;
}

$path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'xmlfile_' . uniqid('', true) . '.xml';
$obj = new Item();
$obj->name = 'test';
$obj->value = 42;

$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;
$dom->preserveWhiteSpace = false;

$pi = $dom->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="style.xsl"');
$dom->appendChild($pi);

$root = $dom->createElement('Item');
$root->appendChild($dom->createElement('name', htmlspecialchars($obj->name, ENT_XML1 | ENT_COMPAT, 'UTF-8')));
$root->appendChild($dom->createElement('value', (string)$obj->value));
$dom->appendChild($root);

file_put_contents($path, $dom->saveXML());

$loaded = new DOMDocument();
$loaded->load($path);
$xpath = new DOMXPath($loaded);
$nodes = $xpath->query('/Item/name');
if ($nodes->length) {
    $name = $nodes->item(0)->textContent;
}
unlink($path);
--EXPECT--
