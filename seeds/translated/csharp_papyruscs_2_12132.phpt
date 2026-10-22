--TEST--
Translated seed (inferredbugs-csharp: papyruscs/2)
--FILE--
<?php
$x = 1;
$z = 1;
$k = (($x & 0xFFFFFFFF) << 32) | ($z & 0xFFFFFFFF);
echo sprintf("%x\n", $k);
?>
--EXPECT--
