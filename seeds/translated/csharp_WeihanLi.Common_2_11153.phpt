--TEST--
Translated seed (inferredbugs-csharp: WeihanLi.Common/2)
--FILE--
<?php
$dt = new DateTimeImmutable('2020-01-01 00:00:00', new DateTimeZone('UTC'));
$rows = [
    ['id' => 1, 'name' => 'a', 'value' => '10', 'when' => $dt],
    ['id' => 2, 'name' => 'b', 'value' => null, 'when' => $dt->modify('+1 day')],
    ['id' => 3, 'name' => 'c', 'value' => '0', 'when' => $dt->modify('+2 day')],
];
$cols = array_column($rows, 'value');
$mapped = array_map(fn($v) => $v === null ? 0 : (int)$v, $cols);
$filtered = array_values(array_filter($mapped, fn($v) => $v >= 0));
$scalar = $filtered[1] ?? 0;
$result = [
    'count' => count($rows),
    'scalar' => $scalar,
    'first' => $rows[0]['name'] ?? '',
    'column' => $filtered,
    'dates' => array_map(fn($r) => $r['when']->format('Y-m-d'), $rows),
];
echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), PHP_EOL;
?>
--EXPECT--
