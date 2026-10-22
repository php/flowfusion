--TEST--
Translated seed (inferredbugs-csharp: Locale-Emulator/1)
--FILE--
<?php
$path = $argv[1] ?? '';
if ($path === '') {
    exit(0);
}
$f = fopen($path, 'rb');
if ($f === false) {
    exit(0);
}
fseek($f, 0x3C);
$buf = fread($f, 4);
if ($buf === false || strlen($buf) < 4) {
    fclose($f);
    exit(0);
}
$e_lfanew = unpack('V', $buf)[1];
fseek($f, $e_lfanew + 4);
$buf = fread($f, 2);
fclose($f);
if ($buf === false || strlen($buf) < 2) {
    exit(0);
}
$machine = unpack('v', $buf)[1];
if ($machine === 0x014C) {
    echo "X32";
} elseif ($machine === 0x8664) {
    echo "X64";
} else {
    echo "Unknown";
}
?>
--EXPECT--
