--TEST--
Translated seed (inferredbugs-csharp: Downloader/12)
--FILE--
<?php

class MemoryStorage {
    private $data = [];
    
    public function write($data, $offset, $length) {
        for ($i = 0; $i < $length; $i++) {
            $this->data[$offset + $i] = $data[$i];
        }
    }
    
    public function getLength() {
        return count($this->data);
    }
}

class Chunk {
    public $Position;
    public $Size;
    public $Storage;

    public function __construct($position, $size) {
        $this->Position = $position;
        $this->Size = $size;
        $this->Storage = null;
    }

    public function clear() {
        $this->Position = 0;
        $this->Storage = null;
    }

    public function isDownloadCompleted() {
        return $this->Position >= $this->Size - 1 && $this->Storage->getLength() == $this->Size;
    }

    public function isValidPosition() {
        if ($this->Storage === null) {
            return $this->Position === 0;
        }
        return $this->Position < $this->Storage->getLength();
    }

    public function setValidPosition() {
        if (!$this->isValidPosition()) {
            $this->Position = 0;
        }
    }
}

function testValidPositionOnOverflow() {
    $size = 1024;
    $chunk = new Chunk($size, $size - 1);
    $chunk->Storage = new MemoryStorage();
    $chunk->Position = $size; // overflowed
    $chunk->setValidPosition();
    echo $chunk->Position; // expects 0
}

function testValidPositionWithNoStorageAndPositivePosition() {
    $size = 1024;
    $chunk = new Chunk(1, $size);
    $chunk->setValidPosition();
    echo $chunk->Position; // expects 0
}

function testValidPositionWhenNoStorageAndZeroPosition() {
    $size = 1024;
    $chunk = new Chunk(0, $size);
    $chunk->setValidPosition();
    echo $chunk->Position; // expects 0
}

testValidPositionOnOverflow();
testValidPositionWithNoStorageAndPositivePosition();
testValidPositionWhenNoStorageAndZeroPosition();
--EXPECT--
