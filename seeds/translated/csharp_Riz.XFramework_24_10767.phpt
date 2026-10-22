--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/24)
--FILE--
<?php
$bindings = [];
if (!$bindings) {
    $bindings = [['member' => 'Id', 'expr' => 1], ['member' => 'Name', 'expr' => 'x']];
}
$sql = '';
for ($i = 0, $n = count($bindings); $i < $n; $i++) {
    $sql .= 't0.' . $bindings[$i]['member'] . ' = ';
    $v = $bindings[$i]['expr'];
    $sql .= is_int($v) ? (string)$v : "'" . str_replace("'", "''", (string)$v) . "'";
    if ($i < $n - 1) {
        $sql .= ",\n";
    }
}
echo $sql;
?>
--EXPECT--
