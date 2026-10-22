--TEST--
Translated seed (inferredbugs-java: FastDFS_Client/3)
--FILE--
<?php

class StorageState {
    // Assume fields and properties of StorageState if necessary
}

class FdfsParamMapper {
    public static function map($data, $class, $charset) {
        // Simulate mapping byte array to StorageState object
        return new $class();
    }

    public static function getObjectMap($class) {
        return new ObjectMetaData();
    }
}

class ObjectMetaData {
    public function getFieldsFixTotalSize() {
        return 10; // Dummy size
    }
}

class TrackerListStoragesResponse {
    private $contentLength;

    public function __construct($contentLength) {
        $this->contentLength = $contentLength;
    }

    public function decodeContent($data) {
        $bytes = unpack('C*', $data);
        if (count($bytes) != $this->contentLength) {
            throw new Exception("读取到的数据长度与协议长度不符");
        }
        return $this->decode($bytes);
    }

    private function decode($bs) {
        $objectMateData = FdfsParamMapper::getObjectMap(StorageState::class);
        $fixFieldsTotalSize = $objectMateData->getFieldsFixTotalSize();
        
        if (count($bs) % $fixFieldsTotalSize != 0) {
            throw new Exception("fixFieldsTotalSize=" . $fixFieldsTotalSize . " but byte array length: " . count($bs) . " is invalid!");
        }

        $count = count($bs) / $fixFieldsTotalSize;
        $results = [];

        for ($i = 0; $i < $count; $i++) {
            $one = array_slice($bs, $i * $fixFieldsTotalSize, $fixFieldsTotalSize);
            $results[] = FdfsParamMapper::map($one, StorageState::class, 'UTF-8');
        }

        return $results;
    }
}

function main() {
    $contentLength = 20; // Example length
    $data = str_repeat("\x00", $contentLength); // Example byte data

    $response = new TrackerListStoragesResponse($contentLength);
    $result = $response->decodeContent($data);
}

main();
--EXPECT--
