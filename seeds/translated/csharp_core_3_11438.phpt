--TEST--
Translated seed (inferredbugs-csharp: core/3)
--FILE--
<?php
$xml = '<node id="1" />';
$node = simplexml_load_string($xml);
echo $node->getName() . "\n";
echo (string)$node['id'] . "\n";

$xml = '<node id="1" lat="54.1" lon="12.2" user="ben" uid="1" version="1" />';
$node = simplexml_load_string($xml);
echo (string)$node['lat'] . "\n";
echo (string)$node['lon'] . "\n";
echo (string)$node['user'] . "\n";
echo (string)$node['uid'] . "\n";
echo (string)$node['version'] . "\n";

$xml = '<osm><node id="1" lat="54.1" lon="12.2" user="ben" uid="1" version="1" /><node id="2" lat="54.1" lon="12.2" user="ben" uid="2" version="1" /></osm>';
$osm = simplexml_load_string($xml);
echo count($osm->node) . "\n";

$dt = new DateTime('2008-09-12T21:37:45Z');
echo $dt->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z') . "\n";
?>
--EXPECT--
