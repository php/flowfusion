--TEST--
Translated seed (inferredbugs-java: FastDFS_Client/6)
--FILE--
<?php

class TrackerListStoragesResponse {

    public function decodeContent($in, $contentLength) {
        $bytes = fread($in, $contentLength);
        if (strlen($bytes) !== $contentLength) {
            throw new RuntimeException("读取到的数据长度与协议长度不符");
        }
        return $this->decode($bytes);
    }

    private function decode($bs) {
        $fixFieldsTotalSize = 10; // Simulating a fixed field size for demonstration
        if (strlen($bs) % $fixFieldsTotalSize !== 0) {
            throw new RuntimeException("fixFieldsTotalSize={$fixFieldsTotalSize} but byte array length: " . strlen($bs) . " is invalid!");
        }
        $count = strlen($bs) / $fixFieldsTotalSize;
        $results = [];

        for ($i = 0; $i < $count; $i++) {
            $one = substr($bs, $i * $fixFieldsTotalSize, $fixFieldsTotalSize);
            $results[] = $this->mapToStorageState($one);
        }

        return $results;
    }

    private function mapToStorageState($data) {
        // Simulated mapping from byte array to StorageState
        return unpack("C*", $data); // Convert bytes to array (simulating StorageState)
    }
}

$inputStream = fopen('php://temp', 'r+');
$fixedData = str_repeat("\x00", 30); // Simulated input data
fwrite($inputStream, $fixedData);
rewind($inputStream);

$response = new TrackerListStoragesResponse();
$response->decodeContent($inputStream, 30); // Correct length
fclose($inputStream);
--EXPECT--
