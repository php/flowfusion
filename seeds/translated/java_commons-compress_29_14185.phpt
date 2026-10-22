--TEST--
Translated seed (inferredbugs-java: commons-compress/29)
--FILE--
<?php

$output = 'bla.cpio';
$file1 = 'test1.xml';
$file2 = 'test2.xml';

$out = fopen($output, 'wb');
$os = fopen('php://temp', 'r+');

fwrite($os, pack('C', 0)); // CPIO header placeholder
fwrite($os, $file1 . "\0" . filesize($file1) . "\0");
fwrite($os, file_get_contents($file1));
fwrite($os, pack('C', 0)); // CPIO entry end placeholder
fwrite($os, $file2 . "\0" . filesize($file2) . "\0");
fwrite($os, file_get_contents($file2));
fwrite($os, pack('C', 0)); // CPIO entry end placeholder

rewind($os);
stream_copy_to_stream($os, $out);
fclose($os);
fclose($out);

$in = fopen($output, 'rb');
$entries = [];

while (!feof($in)) {
    $name = fread($in, 64);
    $length = unpack('V', fread($in, 4))[1];
    
    if ($name === false || $length === false) {
        break;
    }
    $name = rtrim($name, "\0");
    $data = fread($in, $length);
    
    if ($data !== false) {
        file_put_contents($name, $data);
        $entries[$name] = $name;
    }
}

fclose($in);

$t1 = $entries['test1.xml'];
$t2 = $entries['test2.xml'];

$lineSepLength = strlen(PHP_EOL);

if (file_exists($t1) && filesize($t1) === (72 + 4 * $lineSepLength)) {
    // Pass
}

if (file_exists($t2) && filesize($t2) === (73 + 5 * $lineSepLength)) {
    // Pass
}
--EXPECT--
