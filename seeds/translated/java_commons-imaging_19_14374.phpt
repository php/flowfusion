--TEST--
Translated seed (inferredbugs-java: commons-imaging/19)
--FILE--
<?php

$bytes = pack('C*', 0x38, 0x42, 0x49, 0x4D, 0x00, 0x00, 0x00, 0x00, 0x00, 0x01, 0x00, 0x01, 0x00, 0x01, 0x02, 0x00, 0x00, 0x00);

$index = 0;
while ($index + 1 < strlen($bytes)) {
    $tagMarker = ord($bytes[$index++]);
    if ($tagMarker !== 0x1C) {
        break;
    }

    $recordNumber = ord($bytes[$index++]);
    $recordType = ord($bytes[$index++]);
    $recordSize = unpack('n', substr($bytes, $index, 2))[1];
    $index += 2;

    if ($recordSize > 0) {
        $recordData = substr($bytes, $index, $recordSize);
        $index += $recordSize;
    }

    if ($recordNumber === 0x02) {
        // Process the record, for instance, printing the data
        echo "Record Type: " . $recordType . " Data: " . $recordData . PHP_EOL;
    }
}
--EXPECT--
