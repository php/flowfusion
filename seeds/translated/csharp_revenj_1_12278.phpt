--TEST--
Translated seed (inferredbugs-csharp: revenj/1)
--FILE--
<?php
$items = [1, 2, 3, 4];
$len = count($items);
$upper = $len > 0 ? $len : null;
$result = $upper ?? 0;
echo $result, PHP_EOL;
?>
--EXPECT--
