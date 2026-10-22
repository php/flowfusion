--TEST--
Translated seed (inferredbugs-csharp: JUST.net/2)
--FILE--
<?php
$json = '{"a":[{"x":1,"y":"  "},{"x":2,"y":"3"},{"x":-1,"y":null}],"b":{"c":true,"d":1234567890123,"e":1.5,"f":"2020-01-02T03:04:05Z"}}';
$o = json_decode($json, true);

$r = [];
$r[] = array_key_exists('a', $o) ? 'true' : 'false';
$r[] = trim((string)$o['a'][0]['y']) !== '' ? 'true' : 'false';
$r[] = ((string)strtolower((string)1) === (string)strtolower((string)'1')) ? 'T' : 'F';
$r[] = 'ab' . '';
$r[] = substr('abcdef', 2, 3);
$r[] = (string)strpos('hello', 'l');
$r[] = (string)strrpos('hello', 'l');

$parts = ['a', 'b', 'c'];
$s = '';
foreach ($parts as $p) $s .= $p;
$r[] = $s;

$sum = 0.0;
foreach ($o['a'] as $t) $sum += (float)$t['x'];
$r[] = (string)$sum;

$max = null;
foreach ($o['a'] as $t) {
    $v = (float)$t['x'];
    if ($max === null || $v > $max) $max = $v;
}
$r[] = (string)$max;

$min = null;
foreach ($o['a'] as $t) {
    $v = (float)$t['x'];
    if ($min === null || $v < $min) $min = $v;
}
$r[] = (string)$min;

$r[] = (string)count($o['a']);
$r[] = (string)($o['a'][count($o['a']) - 1]['x']);
$r[] = (string)$o['a'][count($o['a']) - 1]['x'];

$group = [];
foreach ($o['a'] as $t) {
    $k = (string)$t['x'];
    if (!isset($group[$k])) $group[$k] = [];
    $group[$k][] = $t;
}
$r[] = json_encode($group);

$r[] = ((float)$o['b']['d'] > (float)$o['a'][1]['x']) ? 'true' : 'false';
$r[] = ((float)$o['b']['e'] <= 2.0) ? 'true' : 'false';
$r[] = ((string)$o['b']['c'] === '1') ? 'true' : 'false';
$r[] = date('Y-m-d\TH:i:sO', strtotime($o['b']['f']));

echo implode("\n", $r), "\n";
?>
--EXPECT--
