--TEST--
Translated seed (inferredbugs-csharp: csharp-driver/12)
--FILE--
<?php
class CqlArgumentException extends Exception {}
class CqlLinqNotSupportedException extends Exception {}
class CqlToken { public array $values; function __construct(array $values){ $this->values = $values; } }

$alter = ['id' => 'id', 'name' => 'name', 'age' => 'age', 'score' => 'score'];
$tableName = 'users';
$where = '';
$orderBy = [];
$limit = 0;
$allowFiltering = false;
$selectFields = [];
$mappings = [];

$token = new CqlToken([1, 2, 3]);
$in = implode(', ', array_map(fn($v) => var_export($v, true), $token->values));
$where .= 'token(' . $in . ') > 0 AND ' . var_export("x", true) . ' = ' . var_export("x", true);
$orderBy[] = $alter['name'] . ' ASC';
$orderBy[] = $alter['age'] . ' DESC';
$limit = 1;
$selectFields[] = 'id';
$mappings['name'] = ['name', 'Alice', 0];
$mappings['age'] = ['age', 42, 1];

$select = 'SELECT ' . implode(', ', array_map(fn($f) => $alter[$f], $selectFields)) . ' FROM ' . $tableName . ' WHERE ' . $where . ' ORDER BY ' . implode(', ', $orderBy) . ' LIMIT ' . $limit;
$updateSet = [];
foreach ($mappings as $k => $m) {
    if (!isset($alter[$k])) throw new CqlArgumentException("Unknown column: $k");
    $updateSet[] = $alter[$k] . ' = ' . var_export($m[1], true);
}
$update = 'UPDATE ' . $tableName . ' SET ' . implode(', ', $updateSet);
$delete = 'DELETE FROM ' . $tableName . ' WHERE ' . $where;

echo $select, "\n", $update, "\n", $delete, "\n";
?>
--EXPECT--
