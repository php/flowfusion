--TEST--
Translated seed (inferredbugs-csharp: kiota/14)
--FILE--
<?php
$position = "0";
$a = [];
$a[$position] = 1;
$b = $a[$position];
echo $b, PHP_EOL;
?>
--EXPECT--
