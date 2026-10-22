--TEST--
Translated seed (inferredbugs-csharp: revenj/2)
--FILE--
<?php
$cases = [
    ['name' => 'o', 'field' => 'items', 'value' => [1, 2, 3]],
    ['name' => 'p', 'field' => 'bytes', 'value' => "\x01\x02\x03\x04"],
    ['name' => 'q', 'field' => 'empty', 'value' => []],
];

foreach ($cases as $c) {
    $v = $c['value'];
    $n = is_string($v) ? strlen($v) : count($v);
    $out = $n > 0 ? $n : 0;
    echo $c['name'] . '.' . $c['field'] . '=' . $out . PHP_EOL;
}
?>
--EXPECT--
