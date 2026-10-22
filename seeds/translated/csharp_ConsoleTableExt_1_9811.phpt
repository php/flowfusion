--TEST--
Translated seed (inferredbugs-csharp: ConsoleTableExt/1)
--FILE--
<?php

function main() {
    $table = [];
    $table[] = [null, "Drug", "Patient", "Date"];

    $row = [999, "y", "str", "x"];
    $table[] = $row;

    $table[] = [null, null, "3", null, "5", "6", "7"];
    $table[] = [null, 1, "2"];
    $table[] = $row;
    $table[] = [["1", 2, 3, 4], ["x", "y", 999]];

    outputTable($table);

    $table = [];
    $table[] = [null, "1", null];
    $table[] = ["1", 2, 3, null];
    $table[] = ["1", null, 2];
    $table[] = ["122"];
    
    outputTable($table);

    $table = [];
    $table[] = ["1", 2, null, 4];
    $table[] = [null, "luong son ba chuc", "anh dai"];
    $table[] = ["133 f afa faf as"];
    
    outputTable($table);

    $table = [];
    $table[] = ["1", null];
    $table[] = [null, "4"];
    
    outputTable($table);
}

function outputTable($table) {
    foreach ($table as $row) {
        echo implode("\t", array_map(function($value) {
            return $value === null ? "NULL" : $value;
        }, $row)) . PHP_EOL;
    }
    echo PHP_EOL;
}

main();
--EXPECT--
