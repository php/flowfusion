--TEST--
Translated seed (inferredbugs-csharp: packetnet/11)
--FILE--
<?php
if (php_sapi_name() !== 'cli') exit(0);

$frame = "\x00\x00\x08\x00\x02\x00\x00\x80" . "\x10\x00" . "\x34\x12" . "\xff";
$len = strlen($frame);
if ($len !== 12) exit(1);

$present = unpack('V', substr($frame, 4, 4))[1];
$flags = unpack('v', substr($frame, 8, 2))[1];
$unknown = substr($frame, 10, 2);
$payload = substr($frame, 12);

if (($present & 0x80000002) !== 0x80000002) exit(1);
if ($flags !== 0x0010) exit(1);
if ($unknown !== "\x34\x12") exit(1);
if ($payload !== "\xff") exit(1);

$frame = substr($frame, 0, 8) . "\x12\x34" . substr($frame, 8);
$len = strlen($frame);
if ($len !== 14) exit(1);

$unknown = substr($frame, $len - 4, 2);
if ($unknown !== "\x34\x12") exit(1);
?>
--EXPECT--
