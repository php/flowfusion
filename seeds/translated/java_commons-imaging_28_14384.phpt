--TEST--
Translated seed (inferredbugs-java: commons-imaging/28)
--FILE--
<?php

$MAGIC_NUMBERS_GIF = [0x47, 0x49];
$MAGIC_NUMBERS_PNG = [0x89, 0x50];
$MAGIC_NUMBERS_JPEG = [0xff, 0xd8];
$MAGIC_NUMBERS_BMP = [0x42, 0x4d];
$MAGIC_NUMBERS_TIFF_MOTOROLA = [0x4D, 0x4D];
$MAGIC_NUMBERS_TIFF_INTEL = [0x49, 0x49];

function compareBytePair($a, $b) {
    if (count($a) !== 2 || count($b) !== 2) {
        throw new Exception("Invalid Byte Pair.");
    }
    return $a[0] === $b[0] && $a[1] === $b[1];
}

function guessFormat($bytes) {
    $bytePair = [ord($bytes[0]), ord($bytes[1])];

    if (compareBytePair($GLOBALS['MAGIC_NUMBERS_GIF'], $bytePair)) return 'GIF';
    if (compareBytePair($GLOBALS['MAGIC_NUMBERS_PNG'], $bytePair)) return 'PNG';
    if (compareBytePair($GLOBALS['MAGIC_NUMBERS_JPEG'], $bytePair)) return 'JPEG';
    if (compareBytePair($GLOBALS['MAGIC_NUMBERS_BMP'], $bytePair)) return 'BMP';
    if (compareBytePair($GLOBALS['MAGIC_NUMBERS_TIFF_MOTOROLA'], $bytePair)) return 'TIFF';
    if (compareBytePair($GLOBALS['MAGIC_NUMBERS_TIFF_INTEL'], $bytePair)) return 'TIFF';

    return 'UNKNOWN';
}

$testBytes = pack("C*", 0xff, 0xd8, 0x00, 0x00); // JPEG magic numbers

echo guessFormat($testBytes);
--EXPECT--
