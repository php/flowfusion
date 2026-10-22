--TEST--
Translated seed (inferredbugs-csharp: csharp-driver/13)
--FILE--
<?php
class CqlArgumentException extends Exception {}
class Table {
    public function __construct(public string $name, public array $cols) {}
}
class V {
    public array $a;
    public function __construct($v) { $this->a = $v; }
}
$t = new Table('my_table', ['id' => 'id', 'name' => 'name', 'age' => 'age', 'score' => 'score']);
$w = [];
$v = [];
$w[] = 'id IN (';
foreach ([1, 2, 3] as $x) { $v[] = $x; $w[] = count($v) - 1 . "\0"; $w[] = ', '; }
array_pop($w);
$w[] = ')';
$w[] = ' AND ';
$w[] = 'NOT (';
$v[] = false;
$w[] = count($v) - 1 . "\0";
$w[] = ')';
$w[] = ' AND ';
$w[] = 'token(';
$v[] = 10;
$w[] = count($v) - 1 . "\0";
$w[] = ', ';
$v[] = 20;
$w[] = count($v) - 1 . "\0";
$w[] = ')';
$where = implode('', $w);
$parts = explode("\0", $where);
$out = '';
$vals = [];
for ($i = 0; $i < count($parts) - 1; $i += 2) {
    $out .= $parts[$i] . ' ? ';
    $vals[] = $v[(int)$parts[$i + 1]];
}
$out .= $parts[count($parts) - 1];
$sql = 'SELECT * FROM ' . $t->name . ' WHERE ' . $out . ' ORDER BY name ASC LIMIT 1 ALLOW FILTERING';
echo $sql, PHP_EOL;
echo json_encode($vals), PHP_EOL;
?>
--EXPECT--
