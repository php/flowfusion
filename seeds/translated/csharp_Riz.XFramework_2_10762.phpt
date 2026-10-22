--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/2)
--FILE--
<?php
$predicates = [
    fn($x) => $x > 0,
    fn($x) => $x < 10,
    fn($x) => ($x & 1) === 0,
];

$body = null;
foreach ($predicates as $p) {
    $body = $body === null ? $p : fn($x) => $body($x) && $p($x);
}

$queries = [
    ['type' => 'select', 'ops' => ['where', 'order', 'take'], 'take' => 1, 'skip' => 0, 'distinct' => false, 'sub' => false],
    ['type' => 'select', 'ops' => ['union', 'order', 'skip'], 'take' => 0, 'skip' => 2, 'distinct' => false, 'sub' => false],
    ['type' => 'update', 'ops' => ['where', 'select'], 'entity' => ['id' => 1]],
    ['type' => 'insert', 'ops' => ['values'], 'entity' => ['id' => 2], 'columns' => ['a', 'b']],
];

$result = [];
foreach ($queries as $q) {
    $where = $body;
    $take = $q['take'] ?? 0;
    $skip = $q['skip'] ?? 0;
    $distinct = $q['distinct'] ?? false;
    $sub = $q['sub'] ?? false;

    if ($take > 0 || ($skip > 0 && !in_array('take', $q['ops'], true)) || $distinct || $sub) {
        $result[] = ['outer' => true, 'type' => $q['type']];
        continue;
    }

    if ($q['type'] === 'update') {
        $result[] = ['update' => $q['entity'], 'where' => $where ? $where(4) : null];
        continue;
    }

    if ($q['type'] === 'insert') {
        $result[] = ['insert' => $q['entity'], 'columns' => $q['columns'] ?? []];
        continue;
    }

    $result[] = ['select' => true, 'where' => $where ? $where(4) : null, 'skip' => $skip, 'take' => $take];
}

echo count($result) . PHP_EOL;
?>
--EXPECT--
