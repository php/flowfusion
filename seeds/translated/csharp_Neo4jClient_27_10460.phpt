--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/27)
--FILE--
<?php
class TestNode {
    public $Foo;
    public $Bar;
    public $Baz;
    public $DateOffSet;
}
class TestNodeWithEnum {
    public $Foo;
    public $Status;
}
$nodeJson = '{"self":"http://foo/db/data/node/456","data":{"Foo":"foo","Bar":"bar","Baz":"baz","DateOffSet":"/Date(1309421746929+0000)/"}}';
$enumJson = '{"self":"http://foo/db/data/node/456","data":{"Foo":"foo","Status":"Value1"}}';
$missing = null;
$d = json_decode($nodeJson, true);
$n = new TestNode();
$n->Foo = $d['data']['Foo'];
$n->Bar = $d['data']['Bar'];
$n->Baz = $d['data']['Baz'];
if (preg_match('/\/Date\((\d+)([+-]\d{4})\)\//', $d['data']['DateOffSet'], $m)) {
    $sec = intdiv((int)$m[1], 1000);
    $n->DateOffSet = new DateTimeImmutable('@' . $sec);
    $n->DateOffSet = $n->DateOffSet->setTimezone(new DateTimeZone('UTC'));
}
$e = json_decode($enumJson, true);
$en = new TestNodeWithEnum();
$en->Foo = $e['data']['Foo'];
$en->Status = $e['data']['Status'] === 'Value1' ? 0 : 1;
echo $n->Foo . $n->Bar . $n->Baz . "\n";
echo $en->Status . "\n";
echo ($n->DateOffSet ? $n->DateOffSet->format('Y-m-d H:i:s\Z') : '') . "\n";
echo ($missing === null ? "null" : "notnull") . "\n";
?>
--EXPECT--
