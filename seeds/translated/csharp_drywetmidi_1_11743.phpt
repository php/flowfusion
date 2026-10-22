--TEST--
Translated seed (inferredbugs-csharp: drywetmidi/1)
--FILE--
<?php
$readVlq = function (string $data, int &$pos): int {
    $value = 0;
    do {
        $b = ord($data[$pos++]);
        $value = ($value << 7) | ($b & 0x7F);
    } while (($b & 0x80) !== 0);
    return $value;
};

$data = chr(0x81) . chr(0x00) . chr(0xF7) . "ABC";
$pos = 0;
$size = $readVlq($data, $pos);
$currentStatusByte = ord($data[$pos++]);

$sysExEvent = null;
switch ($currentStatusByte) {
    case 0xF7:
        $sysExEvent = 'EscapeSysExEvent';
        break;
    case 0xF0:
        $sysExEvent = 'NormalSysExEvent';
        break;
}

if ($sysExEvent === null) {
    $sysExEvent = 'UnknownSysExEvent';
}

$payload = substr($data, $pos, $size);
echo $sysExEvent, ':', strlen($payload), ':', $payload;
?>
--EXPECT--
