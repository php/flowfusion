--TEST--
Translated seed (inferredbugs-java: commons-imaging/10)
--FILE--
<?php

$BMP_HEADER_SIGNATURE = [0x42, 0x4d];
$file = fopen("example.bmp", "rb");
$identifier1 = ord(fread($file, 1));
$identifier2 = ord(fread($file, 1));

if ($identifier1 !== $BMP_HEADER_SIGNATURE[0] || $identifier2 !== $BMP_HEADER_SIGNATURE[1]) {
    die("Not a Valid BMP File");
}

$fileSize = unpack("V", fread($file, 4))[1];
$reserved = unpack("V", fread($file, 4))[1];
$bitmapDataOffset = unpack("V", fread($file, 4))[1];
$bitmapHeaderSize = unpack("V", fread($file, 4))[1];
$width = $height = $planes = $bitsPerPixel = $compression = 0;

if ($bitmapHeaderSize >= 40) {
    $width = unpack("V", fread($file, 4))[1];
    $height = unpack("V", fread($file, 4))[1];
    $planes = unpack("v", fread($file, 2))[1];
    $bitsPerPixel = unpack("v", fread($file, 2))[1];
    $compression = unpack("V", fread($file, 4))[1];
}

$colorTableSize = ($bitsPerPixel <= 8) ? (1 << $bitsPerPixel) : 0;
$colorTable = ($colorTableSize > 0) ? fread($file, $colorTableSize * 4) : null;

$imageDataSize = $height * ((($bitsPerPixel * $width + 31) >> 3) & ~1);
$imageData = fread($file, $imageDataSize);

fclose($file);
--EXPECT--
