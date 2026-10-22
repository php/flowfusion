--TEST--
Translated seed (inferredbugs-java: commons-imaging/14)
--FILE--
<?php

$APP13_BYTE_ORDER = 'N';
$PHOTOSHOP_IDENTIFICATION_STRING = "Photoshop 3.0";
$CONST_8BIM = "8BIM";
$IPTC_RECORD_TAG_MARKER = 0x1C;
$IPTC_APPLICATION_2_RECORD_NUMBER = 0x02;
$IPTC_NON_EXTENDED_RECORD_MAXIMUM_SIZE = 0x7FFF;

function parsePhotoshopSegment($bytes) {
    global $CONST_8BIM, $IPTC_RECORD_TAG_MARKER, $IPTC_APPLICATION_2_RECORD_NUMBER;
    $records = [];
    $index = 0;

    while ($index < count($bytes)) {
        $blockSignature = substr($bytes, $index, strlen($CONST_8BIM));
        $index += strlen($CONST_8BIM);
        
        if ($blockSignature !== $CONST_8BIM) break;

        $recordMarker = ord($bytes[$index++]);
        if ($recordMarker !== $IPTC_RECORD_TAG_MARKER) continue;

        $recordNumber = ord($bytes[$index++]);
        $recordType = ord($bytes[$index++]);

        $recordSize = unpack('n', substr($bytes, $index, 2))[1];
        $index += 2;

        if ($recordSize > $IPTC_NON_EXTENDED_RECORD_MAXIMUM_SIZE) continue;

        $recordData = substr($bytes, $index, $recordSize);
        $index += $recordSize;

        if ($recordNumber !== $IPTC_APPLICATION_2_RECORD_NUMBER) continue;

        $value = mb_convert_encoding($recordData, 'UTF-8', 'ISO-8859-1');
        $records[] = ['type' => $recordType, 'data' => $recordData, 'value' => $value];
    }

    return $records;
}

$bytes = pack('C*', ...array_merge(
    array_map('ord', str_split($PHOTOSHOP_IDENTIFICATION_STRING)),
    array_map('ord', str_split($CONST_8BIM)),
    [0x1C, 0x02, 0x00, 0x02, 0x01] // Example record: type, size, data
));

$parsedRecords = parsePhotoshopSegment($bytes);
--EXPECT--
