--TEST--
Translated seed (inferredbugs-java: Mycat-NIO/1)
--FILE--
<?php

$testS = "test data only for ";
$bufferSize = 1024 * 1024 * 100;
$packageSize = strlen($testS) + 4;
$buffer = str_repeat("\0", $bufferSize);
$readBufferOffset = 0;

$mysqlPackgsStream = createMysgqlPackages();
$curBufOffset = 0;

while (($data = fread($mysqlPackgsStream, $bufferSize)) !== false && strlen($data) > 0) {
    $curBuf = substr($buffer, $curBufOffset);
    if (strlen($curBuf) <= 0) {
        $curBufOffset += $packageSize;
        $curBuf = substr($buffer, $curBufOffset);
    }
    $dataLength = strlen($data);
    $curBuf .= $data;
    $readBufferOffset = parsePackages($curBuf, $readBufferOffset);
}

echo ($readBufferOffset === 10) ? "Success" : "Fail";

function createMysgqlPackages() {
    return fopen("php://temp", "r+");
}

function parsePackages($curBuf, $offset) {
    // Simulate parsing the packages
    return $offset + 1; // Increment for simulation
}
--EXPECT--
