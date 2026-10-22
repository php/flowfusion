--TEST--
Translated seed (inferredbugs-java: TwelveMonkeys/6)
--FILE--
<?php

function writeTiff($width, $height, $compression) {
    $header = pack('v*', 0x4949, 42, 8, 0, $width, $height, 1, 0, 1, 0);
    $data = str_repeat("\0", $width * $height * 3); // RGB
    $compressedData = ($compression === 'none') ? $data : gzcompress($data);
    
    file_put_contents('output.tiff', $header . $compressedData);
}

$width = 100;
$height = 100;
$compression = 'none'; // or 'deflate'

writeTiff($width, $height, $compression);
--EXPECT--
