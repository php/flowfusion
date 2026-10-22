--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/6)
--FILE--
<?php
$joins = [
    ['kind' => 'Join', 'source' => ['table' => 'users'], 'left' => ['id'], 'right' => ['user_id'], 'subquery' => false],
    ['kind' => 'GroupJoin', 'source' => ['table' => 'orders'], 'left' => ['user_id', 'tenant_id'], 'right' => ['id', 'tenant_id'], 'subquery' => true],
    ['kind' => 'SelectMany', 'source' => ['table' => 'logs'], 'left' => [], 'right' => [], 'subquery' => false],
];

$where = '';

foreach ($joins as $j) {
    if ($j['kind'] === 'Join' || $j['kind'] === 'GroupJoin') {
        $alias = 't' . substr(md5($j['source']['table']), 0, 4);
        if ($where !== '') $where .= "\nAND ";
        $where .= "EXISTS(";
        if ($j['subquery']) {
            $where .= "\nSELECT 1 FROM (SELECT * FROM " . $j['source']['table'] . ") " . $alias . " WHERE ";
        } else {
            $where .= "\nSELECT 1 FROM " . $j['source']['table'] . " " . $alias . "\nWHERE ";
        }
        for ($i = 0; $i < count($j['left']); $i++) {
            if ($i > 0) $where .= " AND ";
            $where .= $alias . "." . $j['left'][$i] . " = outer." . $j['right'][$i];
        }
        $where .= "\n)";
    } elseif ($j['kind'] === 'SelectMany') {
        continue;
    }
}

echo $where;
?>
--EXPECT--
