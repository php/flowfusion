--TEST--
Translated seed (inferredbugs-java: Java-Lang/6)
--FILE--
<?php

$filePath = 'testfile.bin';
$mode = 'c+b'; // Read and Write mode
$size = 1024; // Size of the file

$handler = fopen($filePath, $mode);
if (!$handler) {
    die('Failed to open the file');
}

if (fstat($handler)['size'] != $size) {
    ftruncate($handler, $size);
}

fseek($handler, 0);
$address = 0;
$buffers = [];

function sliceAtWithId($address, $size, $id)
{
    global $handler;
    fseek($handler, $address);
    $data = fread($handler, $size);
    return ['data' => $data, 'id' => $id];
}

for ($i = 0; $i < 5; $i++) {
    $buffer = sliceAtWithId($address, 128, $i);
    $buffers[] = $buffer;
    $address += 128;
}

foreach ($buffers as $buffer) {
    echo $buffer['data'];
}

fclose($handler);
--EXPECT--
