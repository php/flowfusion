--TEST--
Translated seed (inferredbugs-java: commons-compress/23)
--FILE--
<?php

$output = "bla.ar";
$file1 = "test1.xml";
$file2 = "test2.xml";

$files = [$file1, $file2];
$archive = fopen($output, 'wb');

foreach ($files as $file) {
    $size = filesize($file);
    fwrite($archive, pack("V", $size)); // Write size (4 bytes)
    fwrite($archive, $file); // Write filename
    fwrite($archive, file_get_contents($file)); // Write file content
}

fclose($archive);

// Unarchive operation
$input = fopen($output, 'rb');
while (!feof($input)) {
    $sizeData = fread($input, 4);
    if (strlen($sizeData) < 4) break;
    $size = unpack("V", $sizeData)[1];
    $filename = fread($input, strlen($file1)); // Assuming consistent filename length for simplicity
    $content = fread($input, $size);
    file_put_contents($filename, $content);
}

fclose($input);
--EXPECT--
