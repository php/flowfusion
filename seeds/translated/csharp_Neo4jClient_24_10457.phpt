--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/24)
--FILE--
<?php
$nodeJson = '{"self":"http://foo/db/data/node/456","data":{"Foo":"foo","Bar":"bar","Baz":"baz","Status":"Value1","DateOffSet":"2011-06-30T08:15:46+00:00"}}';
$node = json_decode($nodeJson, true);
if (!is_array($node) || !isset($node['data'])) exit(1);
$data = $node['data'];
if ($data['Foo'] !== 'foo' || $data['Bar'] !== 'bar' || $data['Baz'] !== 'baz') exit(1);
if ($data['Status'] !== 'Value1') exit(1);
$dt = new DateTimeImmutable($data['DateOffSet']);
if ($dt->format('Y-m-d H:i:s\Z') !== '2011-06-30 08:15:46Z') exit(1);
$missing = json_decode('null', true);
if ($missing !== null) exit(1);
echo "";
?>
--EXPECT--
