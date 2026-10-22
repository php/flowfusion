--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/40)
--FILE--
<?php
$joins = [
    ['type' => 'LeftOuterJoin', 'kind' => 'new', 'left' => ['a.id', 'a.name'], 'right' => ['b.id', 'b.name'], 'table' => 'users'],
    ['type' => 'SelectMany', 'kind' => 'cross', 'table' => 'orders'],
    ['type' => 'Join', 'kind' => 'member', 'left' => 'x.id', 'right' => 'y.id', 'table' => 'items'],
];

$aliases = [];
$nextAlias = 0;
$sql = '';

foreach ($joins as $j) {
    $sql .= PHP_EOL;
    $type = $j['type'] === 'LeftOuterJoin' ? 'LEFT JOIN' : ($j['type'] === 'RightOuterJoin' ? 'RIGHT JOIN' : ($j['type'] === 'SelectMany' ? 'CROSS JOIN' : 'INNER JOIN'));
    $sql .= $type;

    if ($j['type'] === 'SelectMany') {
        $alias = $aliases[$j['table']] ?? ('t' . $nextAlias++);
        $aliases[$j['table']] = $alias;
        $sql .= ' ' . $j['table'] . ' ' . $alias . ' ';
        continue;
    }

    $alias = $aliases[$j['table']] ?? ('t' . $nextAlias++);
    $aliases[$j['table']] = $alias;
    $sql .= ' ' . $j['table'] . ' ' . $alias . ' ON ';

    if ($j['kind'] === 'new') {
        for ($i = 0, $n = count($j['left']); $i < $n; $i++) {
            $sql .= $j['left'][$i] . ' = ' . $j['right'][$i];
            if ($i < $n - 1) $sql .= ' AND ';
        }
    } else {
        $sql .= $j['left'] . ' = ' . $j['right'];
    }
}

echo $sql;
?>
--EXPECT--
