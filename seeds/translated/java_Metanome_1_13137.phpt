--TEST--
Translated seed (inferredbugs-java: Metanome/1)
--FILE--
<?php

$fileName = 'output.txt';
$file = fopen($fileName, 'a');

$columnCombination = "Column1, Column2, Column3"; // Simulate a ColumnCombination

fwrite($file, $columnCombination . "\n");
fclose($file);
--EXPECT--
