--TEST--
Translated seed (inferredbugs-csharp: Pomelo.EntityFrameworkCore.MySql/2)
--FILE--
<?php
$cases = [
    ["schema" => "s1", "name" => "fk1"],
    ["schema" => null, "name" => "fk2"],
    ["schema" => "s2", "name" => null],
];

foreach ($cases as $op) {
    $schema = null;
    foreach (get_object_vars((object)$op) as $k => $v) {
        if (strpos($k, 'schema') !== false && $v !== null) {
            $schema = $v;
            break;
        }
    }
    if ($schema !== null) {
        $name = $op["name"] ?? null;
        $msg = 'A schema "' . $schema . '" has been set for an object of type "stdClass"';
        if ($name !== null && $name !== '') {
            $msg .= ' with the name of "' . $name . '"';
        }
        $msg .= '. MySQL does not support the EF Core concept of schemas.';
    }
}

$types = [
    "varchar(255)",
    "int(11)",
    "geometry",
    "json",
    "timestamp(6)",
    "varchar CHARACTER SET utf8mb4 COLLATE utf8mb4_bin",
];

foreach ($types as $t) {
    $m = [];
    if (preg_match('/(?<Name>[a-z0-9]+)\s*?(?:\(\s*(?<Length>\d+)?\s*\))?/i', $t, $m)) {
        $name = strtolower($m['Name']);
        $len = $m['Length'] ?? '';
        if ($name === 'timestamp' && $len === '6') {
            $sql = 'CURRENT_TIMESTAMP(' . $len . ')';
        }
    }
    if (stripos($t, 'json') !== false) {
        $out = $t;
    } else {
        $out = preg_replace('/(CHARACTER SET|CHARSET)\s+\w+/i', 'CHARACTER SET utf8mb4', $t);
        $out = preg_replace('/COLLATE\s+\w+/i', 'COLLATE utf8mb4_bin', $out);
    }
}

$columns = ['id', 'parent_id', 'created_at'];
$prefix = [0, 10, 0];
$sql = 'CREATE UNIQUE INDEX `idx` ON `t` (' . implode(', ', array_map(function ($c, $i) use ($prefix) {
    return '`' . $c . '`' . (($prefix[$i] ?? 0) > 0 ? '(' . $prefix[$i] . ')' : '');
}, $columns, array_keys($columns))) . ')';

echo "";
?>
--EXPECT--
