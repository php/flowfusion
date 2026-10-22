--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/1)
--FILE--
<?php
$bindings = [
    ['member' => 'Id', 'expr' => 1],
    ['member' => 'Name', 'expr' => 'Alice'],
    ['member' => 'Score', 'expr' => 1 + 2],
];

$out = '';
foreach ($bindings as $i => $b) {
    $out .= 't0.' . $b['member'] . ' = ';
    $v = $b['expr'];
    if (is_int($v) || is_float($v) || is_string($v) || is_bool($v) || $v === null) {
        $out .= var_export($v, true);
    } else {
        $out .= json_encode($v);
    }
    if ($i < count($bindings) - 1) {
        $out .= ',' . PHP_EOL;
    }
}

$anon = [
    'Id' => 42,
    'Title' => 'X',
];

foreach ($anon as $k => $v) {
    $out .= PHP_EOL . 't0.' . $k . ' = ' . var_export($v, true);
}

echo $out;
?>
--EXPECT--
