--TEST--
Translated seed (inferredbugs-csharp: ConsoleTableExt/2)
--FILE--
<?php

function getTable() {
    $table = [];
    $table[] = [null, "Indocin", "David", date('Y-m-d H:i:s')];
    $table[] = [50, null, "Sam", date('Y-m-d H:i:s')];
    $table[] = [10, "Hydralazine", null, date('Y-m-d H:i:s')];
    $table[] = [21, "Combivent", "Janet", date('Y-m-d H:i:s')];
    $table[] = [100, "Dilantin", "Melanie", null];
    return $table;
}

$table = [];
$table[] = [null, "Drug", "Patient", "Date"];
$table[] = [999, "y", "str", "x"];
$table[] = [null, null, "3", null, "5", "6", "7"];
$table[] = [null, 1, "2"];
$table = array_merge($table, getTable());

foreach ($table as $row) {
    echo implode(", ", array_map(function($item) {
        return $item === null ? "NULL" : $item;
    }, $row)) . PHP_EOL;
}

$table2 = [];
$table2[] = [null, "1", null];
$table2[] = [null, "helo", "helo 1", "helo 2", "1", "2", "3", "4", "5"];
$table2[] = [null, null];
$table2[] = [null, null];

foreach ($table2 as $row) {
    echo implode(", ", array_map(function($item) {
        return $item === null ? "NULL" : $item;
    }, $row)) . PHP_EOL;
}

$table3 = [];
$table3[] = [1, 2, null, 4];
$table3[] = [null, "luong son ba chuc", "anh dai"];
$table3[] = ["133 f afa faf as"];

foreach ($table3 as $row) {
    echo implode(", ", array_map(function($item) {
        return $item === null ? "NULL" : $item;
    }, $row)) . PHP_EOL;
}

$table4 = [];
$table4[] = [null, null];
$table4[] = [null, null];

foreach ($table4 as $row) {
    echo implode(", ", array_map(function($item) {
        return $item === null ? "NULL" : $item;
    }, $row)) . PHP_EOL;
}
--EXPECT--
