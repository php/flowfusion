--TEST--
Translated seed (inferredbugs-java: commons-compress/36)
--FILE--
<?php

$output = 'bla.ar';
$file1 = 'test1.xml';
$file2 = 'test2.xml';

// Create archive
$out = fopen($output, 'wb');
$os = fopen($output, 'ab');

fwrite($os, pack('N', strlen($file1)) . $file1);
fwrite($os, file_get_contents($file1));
fwrite($os, pack('N', strlen($file2)) . $file2);
fwrite($os, file_get_contents($file2));

fclose($os);

// Unarchive operation
$input = fopen($output, 'rb');
while (!feof($input)) {
    $nameLength = unpack('N', fread($input, 4))[1];
    if ($nameLength === 0) break;
    $name = fread($input, $nameLength);
    $content = fread($input, filesize($output) - ftell($input));
    file_put_contents($name, $content);
}

fclose($input);

// Delete operation (remove all but one file)
$output2 = 'bla2.ar';
$is = fopen($output, 'rb');
$os = fopen($output2, 'wb');

while (!feof($is)) {
    $nameLength = unpack('N', fread($is, 4))[1];
    if ($nameLength === 0) break;
    $name = fread($is, $nameLength);
    $content = fread($is, filesize($output) - ftell($is));
    if ($name === 'test1.xml') {
        fwrite($os, pack('N', $nameLength) . $name);
        fwrite($os, $content);
    }
}

fclose($os);
fclose($is);

// Sum lengths of files in the new archive
$sum = 0;
$is = fopen($output2, 'rb');

while (!feof($is)) {
    $nameLength = unpack('N', fread($is, 4))[1];
    if ($nameLength === 0) break;
    $name = fread($is, $nameLength);
    $content = fread($is, filesize($output2) - ftell($is));
    $sum += strlen($content);
}

fclose($is);
--EXPECT--
