--TEST--
Translated seed (inferredbugs-csharp: csharp-driver/8)
--FILE--
<?php
$rows = [];
$ks = 'tester';
$table = $ks . '.test';

if (!isset($rows[$table])) {
    $rows[$table] = [];
}
$rows[$table]['123'] = 17;
$rows[$table]['124'] = 18;

$prepared = function (string $k) use (&$rows, $table): int {
    return $rows[$table][$k]['i'];
};

$rows[$table]['123'] = ['i' => 17];
$rows[$table]['124'] = ['i' => 18];

if ($prepared('123') !== 17) {
    throw new Exception('unexpected');
}

$nodeUp = true;
$nodeUp = false;
$nodeUp = true;

if (!$nodeUp) {
    throw new Exception('unexpected');
}

if ($prepared('124') !== 18) {
    throw new Exception('unexpected');
}
?>
--EXPECT--
