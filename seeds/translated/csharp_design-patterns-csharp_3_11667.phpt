--TEST--
Translated seed (inferredbugs-csharp: design-patterns-csharp/3)
--FILE--
<?php
$data = ["a", "b", "c", "d", "e"];

sort($data, SORT_STRING);
echo implode(",", $data), ",\n";

rsort($data, SORT_STRING);
echo implode(",", $data), ",\n";
?>
--EXPECT--
