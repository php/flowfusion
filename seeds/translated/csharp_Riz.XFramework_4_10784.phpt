--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/4)
--FILE--
<?php
declare(strict_types=1);

$joins = [
    ['type' => 'Join', 'source' => ['table' => 'users'], 'left' => ['id'], 'right' => ['user_id'], 'alias' => 'u'],
    ['type' => 'GroupJoin', 'source' => ['subquery' => 'SELECT id, user_id FROM orders WHERE status = ?'], 'left' => ['id', 'tenant_id'], 'right' => ['user_id', 'tenant_id'], 'alias' => 'o'],
];

$where = '';
$on = '';

foreach ($joins as $j) {
    $useExists = true;
    if ($where !== '' || $on !== '') {
        $where .= PHP_EOL;
        if ($where !== '') $where .= 'AND ';
    }

    $where .= 'EXISTS(';
    if (isset($j['source']['table'])) {
        $where .= PHP_EOL . 'SELECT 1 FROM ' . $j['source']['table'] . ' ' . $j['alias'] . PHP_EOL . 'WHERE ';
    } else {
        $where .= PHP_EOL . 'SELECT 1 FROM(' . $j['source']['subquery'] . ')' . ' ' . $j['alias'] . ' WHERE ';
    }

    $pairs = [];
    foreach ($j['left'] as $i => $left) {
        $pairs[] = $j['alias'] . '.' . $left . ' = ' . $j['alias'] . '.' . $j['right'][$i];
    }

    $where .= implode(' AND ', $pairs);
    if ($useExists) {
        $where .= PHP_EOL . ')';
    }
}

echo $where;
?>
--EXPECT--
