--TEST--
Translated seed (inferredbugs-csharp: csharp-driver/9)
--FILE--
<?php
$rows = [
    '123' => 17,
    '124' => 18,
];

$prepared = function (string $k) use (&$rows): array {
    return ['k' => $k, 'i' => $rows[$k] ?? null];
};

$r1 = $prepared('123');
if ($r1['i'] !== 17) {
    throw new Exception('unexpected');
}

$down = true;
$up = false;
$up = true;

$r2 = $prepared('124');
if ($r2['i'] !== 18) {
    throw new Exception('unexpected');
}
?>
--EXPECT--
