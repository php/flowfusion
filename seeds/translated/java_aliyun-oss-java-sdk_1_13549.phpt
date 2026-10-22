--TEST--
Translated seed (inferredbugs-java: aliyun-oss-java-sdk/1)
--FILE--
<?php

$key = "append-object-crc-neg";
$content = "Hello OSS, Hi OSS.";
$initCRC = 1;

try {
    $position = 0;
    
    $clientCRC = $initCRC; // simulate initial CRC
    $nextPosition = strlen($content); // simulate next position

    // Simulate append operation
    if ($clientCRC !== 0) {
        throw new Exception("Append object should not be successful.");
    }

} catch (Exception $e) {
    if ($e->getMessage() === "Append object should not be successful.") {
        echo $e->getMessage();
    }
}
--EXPECT--
