--TEST--
Translated seed (inferredbugs-csharp: SqlSugar/2)
--FILE--
<?php
$p = 1;
$student = ['Id' => 42];
$result1 = ['Name' => 'a', 'Id' => $student['Id'], 'SchoolId' => $p];
$selectorValue1 = json_encode($result1);
$pars1 = array_values($result1);

$a = 'a';
$result2 = ['x' => $student['Id'], 'shoolid' => 1, 'name' => $a];
$selectorValue2 = json_encode($result2);
$pars2 = array_values($result2);
?>
--EXPECT--
