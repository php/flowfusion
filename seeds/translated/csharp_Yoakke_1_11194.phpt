--TEST--
Translated seed (inferredbugs-csharp: Yoakke/1)
--FILE--
<?php
$ecx = 1000;
$edx = 7;
$addr = $ecx + ($edx * 4) + 23;
echo "add eax, dword ptr [ecx + edx*4 + 23]\n";
echo "effective_address=$addr\n";
?>
--EXPECT--
