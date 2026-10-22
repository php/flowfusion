--TEST--
Translated seed (inferredbugs-csharp: packetnet/10)
--FILE--
<?php
$bytes = function(array $a): string { return implode('', array_map(fn($v) => chr($v), $a)); };

$payload = "\xFF";
$frame = "\x00\x00\x09\x00\x02\x00\x00\x80\x00\x10\x00\x00" . $payload;
if (strlen($frame) !== 13) exit(1);
if (substr($frame, -1) !== $payload) exit(1);

$radioTap = "\x00\x00\x10\x00\x02\x00\x00\x80\x00\x00\x01\x00\x10\x00\x34\x12";
if (strlen($radioTap) !== 16) exit(1);
if (unpack('v', substr($radioTap, -2))[1] !== 0x1234) exit(1);

$tsft = pack('P', 0x123456789);
if (strlen($tsft) < 8) $tsft = str_pad($tsft, 8, "\x00");
$final = substr($radioTap, 0, 8) . $tsft . substr($radioTap, 16);
if (strlen($final) < 24) $final = str_pad($final, 24, "\x00");
if (unpack('v', substr($final, -2))[1] !== 0x1234) exit(1);
?>
--EXPECT--
