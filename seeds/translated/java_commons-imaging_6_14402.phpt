--TEST--
Translated seed (inferredbugs-java: commons-imaging/6)
--FILE--
<?php

function guessFormat($bytes) {
    $b1 = $bytes[0] & 0xff;
    $b2 = $bytes[1] & 0xff;

    if ($b1 == 0x47 && $b2 == 0x49) {
        return 'GIF';
    } elseif ($b1 == 0x89 && $b2 == 0x50) {
        return 'PNG';
    } elseif ($b1 == 0xff && $b2 == 0xd8) {
        return 'JPEG';
    } elseif ($b1 == 0x42 && $b2 == 0x4d) {
        return 'BMP';
    } elseif ($b1 == 0x4D && $b2 == 0x4D || $b1 == 0x49 && $b2 == 0x49) {
        return 'TIFF';
    } elseif ($b1 == 0x38 && $b2 == 0x42) {
        return 'PSD';
    }
    return 'UNKNOWN';
}

$bytes = file_get_contents("path/to/image/file");
$bytes = array_values(unpack('C*', $bytes)); // Convert to array of bytes

$format = guessFormat($bytes);
echo $format;
?>
--EXPECT--
