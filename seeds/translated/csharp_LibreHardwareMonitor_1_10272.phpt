--TEST--
Translated seed (inferredbugs-csharp: LibreHardwareMonitor/1)
--FILE--
<?php
$bus = ((0x12 & 0xFF) << 8) | ((0x1F & 0x1F) << 3) | (0x07 & 7);
$reg = 4;
$ok = (($reg & 3) === 0);
$msr = (0x12345678 << 32) | 0x9ABCDEF0;
$eax = $msr & 0xFFFFFFFF;
$edx = ($msr >> 32) & 0xFFFFFFFF;
$io = $bus ^ $reg ^ $eax ^ $edx;
$mem = pack('V2', $eax, $edx);
$len = strlen($mem);
$ref = substr($mem, 0, $len);
echo $ok ? "" : "";
echo $io . $ref;
?>
--EXPECT--
