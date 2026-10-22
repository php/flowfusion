--TEST--
Translated seed (inferredbugs-csharp: csharp-driver/2)
--FILE--
<?php
declare(strict_types=1);

$rows = [[42]];
$cols = [['Name' => 'x']];
if (count($rows) !== 1 || count($cols) !== 1) {
    throw new RuntimeException();
}
$colToIdx = [];
foreach ($cols as $idx => $col) {
    $colToIdx[$col['Name']] = $idx;
}
foreach ($rows as $row) {
    $value = $row[$colToIdx['x']];
    echo $value, PHP_EOL;
}
--EXPECT--
