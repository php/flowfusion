--TEST--
Translated seed (inferredbugs-csharp: Downloader/36)
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

    public function clear() {
        $this->data = [];
    }
}

class Chunk {
    public $Position;
    private $MaxSize;
    public $Storage;

    public function __construct($start, $size) {
        $this->Position = $start;
        $this->MaxSize = $size;
    }

    public function clear() {
        $this->Position = 0;
        if ($this->Storage) {
            $this->Storage->clear();
        }
    }

    public function isValidPosition() {
        if (!$this->Storage) {
            return $this->Position === 0;
        }
        return $this->Position >= 0 && $this->Position < $this->Storage->getLength();
    }

    public function setValidPosition() {
        if (!$this->isValidPosition()) {
            $this->Position = 0;
        }
    }
}

$testData = array_fill(0, 1024, 1);

$chunk = new Chunk(0, count($testData) - 1);
$chunk->Storage = new MemoryStorage();

$chunk->Storage->write($testData, 0, 5);
$chunk->clear();
echo "Storage length after clear: " . $chunk->Storage->getLength() . "\n"; // expected: 0

$chunk->Position = count($testData);
$chunk->setValidPosition();
echo "Position after overflow set: " . $chunk->Position . "\n"; // expected: 0

$chunk->Position = 10;
$chunk->Storage->write($testData, 0, 10);
echo "Is valid position with data written: " . ($chunk->isValidPosition() ? "true" : "false") . "\n"; // expected: true
--EXPECT--
