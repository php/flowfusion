--TEST--
Translated seed (inferredbugs-csharp: core/1)
--FILE--
<?php
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><osm version="0.6" generator="OsmSharp"></osm>');
$n = $xml->addChild('node');
$n->addAttribute('id', '1');
$w = $xml->addChild('way');
$w->addAttribute('id', '1');
$w->addChild('nd')->addAttribute('ref', '1');
$w->addChild('nd')->addAttribute('ref', '2');
$w->addChild('nd')->addAttribute('ref', '3');
$r = $xml->addChild('relation');
$r->addAttribute('id', '1');
$m = $r->addChild('member');
$m->addAttribute('type', 'node');
$m->addAttribute('ref', '1');
$m->addAttribute('role', '');
echo $xml->asXML();
?>
--EXPECT--
