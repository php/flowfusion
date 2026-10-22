--TEST--
Translated seed (inferredbugs-java: dd-plist/3)
--FILE--
<?php
$xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist>
<dict>
<key>name</key><string>Test</string>
<key>numbers</key><array>
<integer>1</integer>
<integer>2</integer>
<integer>3</integer>
</array>
</dict>
</plist>
XML;

$dom = new DOMDocument();
@$dom->loadXML($xml);
if ($dom->doctype->name !== 'plist') {
    throw new Exception("The given XML document is not a property list.");
}
$root = $dom->documentElement->firstChild;

function parseNode($node) {
    switch ($node->nodeName) {
        case 'dict':
            $dict = [];
            foreach ($node->childNodes as $child) {
                if ($child->nodeName === 'key') {
                    $key = $child->textContent;
                    $value = parseNode($child->nextSibling);
                    $dict[$key] = $value;
                }
            }
            return $dict;
        case 'array':
            $array = [];
            foreach ($node->childNodes as $child) {
                if ($child->nodeName !== '#text') {
                    $array[] = parseNode($child);
                }
            }
            return $array;
        case 'true':
            return true;
        case 'false':
            return false;
        case 'integer':
            return (int)$node->textContent;
        case 'real':
            return (float)$node->textContent;
        case 'string':
            return $node->textContent ?: '';
        default:
            return null;
    }
}

$parsedData = parseNode($root);
--EXPECT--
