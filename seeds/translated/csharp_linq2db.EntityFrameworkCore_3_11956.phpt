--TEST--
Translated seed (inferredbugs-csharp: linq2db.EntityFrameworkCore/3)
--FILE--
<?php
$items = [
    ['id' => 1, 'name' => 'a'],
    ['id' => 2, 'name' => 'b'],
    ['id' => 3, 'name' => 'c'],
];

$batchSize = 2;
$batches = [];
for ($i = 0, $n = count($items); $i < $n; $i += $batchSize) {
    $batches[] = array_slice($items, $i, $batchSize);
}

$out = [];
foreach ($batches as $batch) {
    foreach ($batch as $row) {
        $out[] = $row;
    }
}

$table = [];
foreach ($out as $row) {
    $table[] = $row;
}

echo count($table), PHP_EOL;
--EXPECT--
