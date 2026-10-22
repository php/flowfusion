--TEST--
Translated seed (inferredbugs-java: UA-Java-Legacy/3)
--FILE--
<?php
$handshakeTimeout = 10 * 60 * 1000; // 10 minutes
$maxRecvChunkSize = 1048576; // 1 MB
$maxSendChunkSize = 1048576; // 1 MB

$inputStream = fopen('php://temp', 'r+');
fwrite($inputStream, pack('P', 8) . pack('P', 12)); // Example header with chunk size
rewind($inputStream);

$inputAvailable = stream_get_contents($inputStream, 4096);
$chunkSize = unpack('P', substr($inputAvailable, 8, 8))[1];

if ($chunkSize < 12) {
    echo "Error: Bad_TcpInternalError\n";
} elseif ($chunkSize > $maxRecvChunkSize) {
    echo "Error: Bad_TcpMessageTooLarge - Chunk size ($chunkSize) exceeded maximum ($maxRecvChunkSize)\n";
} elseif (strlen($inputAvailable) >= $chunkSize) {
    $chunk = substr($inputAvailable, 0, $chunkSize);
    // Process the chunk as needed
    echo "Chunk is readable: " . bin2hex($chunk) . "\n";
} else {
    echo "Chunk not fully available, wait for more data.\n";
}

fclose($inputStream);
--EXPECT--
