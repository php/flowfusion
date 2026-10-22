--TEST--
Translated seed (inferredbugs-java: InflatableDonkey/1)
--FILE--
<?php

$inputStream = fopen('php://temp', 'r+');
$chunkInfos = [['length' => 5, 'checksum' => 'test1'], ['length' => 3, 'checksum' => 'test2']];
$chunks = [];

foreach ($chunkInfos as $index => $chunkInfo) {
    fseek($inputStream, 0);
    fwrite($inputStream, str_repeat('A', $chunkInfo['length']));
    fseek($inputStream, 0);
    
    $checksum = $chunkInfo['checksum'];
    $chunk = decrypt($inputStream, $checksum, $index);
    if ($chunk !== null) {
        $chunks[$checksum] = $chunk;
    }
}

fclose($inputStream);

function decrypt($inputStream, $checksum, $index) {
    $decryptedData = stream_get_contents($inputStream);
    return $decryptedData === false ? null : $decryptedData;
}
--EXPECT--
