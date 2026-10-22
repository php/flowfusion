--TEST--
Translated seed (inferredbugs-csharp: csharp-driver/11)
--FILE--
<?php
$streamId = 255;
$cqlQuery = str_repeat("a", 65535);
$consistency = 0;
$tracingEnabled = true;

$flags = $tracingEnabled ? 0x02 : 0x00;
$frame = pack('CCCCC', 0x04, $flags, $streamId & 0xFF, 0x07, 0x00)
    . pack('N', strlen($cqlQuery)) . $cqlQuery
    . pack('n', $consistency & 0xFFFF)
    . chr(0x00);

$batch = chr(0x00)
    . pack('N', strlen($cqlQuery)) . $cqlQuery
    . pack('n', 0);

echo strlen($frame) . "\n";
echo strlen($batch) . "\n";
echo ord($frame[1]) . "\n";
echo ord($frame[2]) . "\n";
echo ord($frame[3]) . "\n";
echo ord($frame[4]) . "\n";
echo substr($frame, 5, 4) === pack('N', 65535) ? "1\n" : "0\n";
echo substr($batch, 0, 1) === "\x00" ? "1\n" : "0\n";
--EXPECT--
