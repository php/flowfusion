--TEST--
Translated seed (inferredbugs-csharp: Downloader/34)
--FILE--
<?php

class MemoryStorage {
    private $data = [];
    
    public function write($data, $offset) {
        for ($i = 0; $i < count($data); $i++) {
            $this->data[$offset + $i] = $data[$i];
        }
    }

    public function getLength() {
        return count($this->data);
    }
}

class Chunk {
    public $Position;
    public $MaxSize;
    public $Storage;

    public function __construct($start, $maxSize) {
        $this->Position = $start;
        $this->MaxSize = $maxSize;
        $this->Storage = null;
    }

    public function clear() {
        $this->Position = 0;
        $this->Storage = null;
    }

    public function isValidPosition() {
        if ($this->Position < 0 || ($this->Storage && $this->Position > $this->Storage->getLength())) {
            return false;
        }
        return true;
    }

    public function setValidPosition() {
        if (!$this->isValidPosition()) {
            $this->Position = 0;
        }
    }
}

$size = 1024;
$chunk = new Chunk(0, $size);

// Test valid position with memory storage
$chunk->Storage = new MemoryStorage();
$chunk->Storage->write([0x1, 0x2, 0x3, 0x4, 0x5, 0x6, 0x7], 0);
$chunk->Position = 10; // Set to an invalid position
$chunk->setValidPosition(); // Should set position to 0

echo $chunk->Position; // Output should be 0

$chunk->clear(); // Clear the chunk
$chunk->setValidPosition(); // Should still be valid and set position to 0

echo $chunk->Position; // Output should be 0 again
--EXPECT--
