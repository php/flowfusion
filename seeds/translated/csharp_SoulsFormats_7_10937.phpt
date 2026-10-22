--TEST--
Translated seed (inferredbugs-csharp: SoulsFormats/7)
--FILE--
<?php
$bhd = "BHF3" . str_pad("12345678", 8, "\0") . chr(0x74) . "\0\0\0" . pack("V", 1) . pack("V", 0) . pack("V", 0) . pack("V", 0) . chr(0x40) . "\0\0\0" . pack("V", 3) . pack("V", 0x20) . pack("V", 0) . pack("V", 0x30) . pack("V", 3) . "abc\0";
$bdt = "BDF3" . str_pad("87654321", 8, "\0") . pack("V", 0) . "abc";
$bhdBytes = substr($bhd, 0, 4) === "BHF3" ? $bhd : "";
$bdtBytes = substr($bdt, 0, 4) === "BDF3" ? $bdt : "";
$bhdSize = strlen($bhdBytes);
$bdtSize = strlen($bdtBytes);
$magic1 = substr($bhdBytes, 0, 4);
$magic2 = substr($bdtBytes, 0, 4);
$timestamp1 = rtrim(substr($bhdBytes, 4, 8), "\0");
$timestamp2 = rtrim(substr($bdtBytes, 4, 8), "\0");
$format = ord($bhdBytes[12]);
$fileCount = unpack("V", substr($bhdBytes, 16, 4))[1];
$offset = unpack("V", substr($bhdBytes, 32, 4))[1];
$size = unpack("V", substr($bhdBytes, 28, 4))[1];
$name = substr($bhdBytes, $offset, 3);
$data = substr($bdtBytes, 16 + $offset - 0x30, $size);
echo $magic1, $magic2, $timestamp1, $timestamp2, $format, $fileCount, $name, $data;
?>
--EXPECT--
