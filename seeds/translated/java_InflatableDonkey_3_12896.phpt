--TEST--
Translated seed (inferredbugs-java: InflatableDonkey/3)
--FILE--
<?php

$inputStream = fopen('php://temp', 'r+');
$chunkInfos = [
    ["length" => 10, "checksum" => 'chunk1', "encryptionKey" => 'key1'],
    ["length" => 15, "checksum" => 'chunk2', "encryptionKey" => 'key2'],
];

$chunks = [];
foreach ($chunkInfos as $index => $chunkInfo) {
    fseek($inputStream, 0);
    $bis = fopen('php://temp', 'r+');
    fwrite($bis, str_repeat('A', $chunkInfo['length']));
    fseek($bis, 0);

    $chunk = decrypt($bis, $chunkInfo['encryptionKey'], $chunkInfo['checksum'], $index);
    if ($chunk !== null) {
        $chunks[$chunkInfo['checksum']] = $chunk;
    }
}

fclose($inputStream);

function decrypt($bis, $encryptionKey, $checksum, $index) {
    // Simulate key unwrapping and decryption
    if ($encryptionKey === 'key1') {
        return fread($bis, 10) . ' decrypted';
    }
    return null;
}
--EXPECT--
