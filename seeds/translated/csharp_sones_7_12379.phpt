--TEST--
Translated seed (inferredbugs-csharp: sones/7)
--FILE--
<?php
$idx = [];
$docs = [
    ['id' => '1', 'indexId' => 'a', 'vertexId' => 9223372036854775807, 'propertyId' => null, 'Text' => 'alpha beta'],
    ['id' => '2', 'indexId' => 'a', 'vertexId' => 0, 'propertyId' => 42, 'Text' => 'alpha'],
    ['id' => '3', 'indexId' => 'b', 'vertexId' => -1, 'propertyId' => 42, 'Text' => 'beta gamma'],
];
foreach ($docs as $d) {
    $idx[$d['id']] = $d;
}
$q = 'alpha';
$inner = 'a';
$res = [];
foreach ($idx as $d) {
    if (strpos($d['Text'], $q) !== false && strpos($d['indexId'], $inner) !== false) {
        $res[] = $d;
    }
}
$has = !empty($res);
$keys = [];
$vals = [];
foreach ($idx as $d) {
    $keys[] = $d['Text'];
    $vals[(string)$d['vertexId']] = true;
}
unset($idx['2']);
if (isset($idx['2'])) {
    $idx['2']['Text'] = 'x';
}
$idx = [];
foreach ($docs as $d) {
    $idx[$d['id']] = $d;
}
echo ($has ? "1" : "0") . "\n";
echo count($res) . "\n";
echo count($keys) . "\n";
echo count($vals) . "\n";
echo count($idx) . "\n";
?>
--EXPECT--
