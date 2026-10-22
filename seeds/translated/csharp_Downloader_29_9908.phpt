--TEST--
Translated seed (inferredbugs-csharp: Downloader/29)
--FILE--
<?php

class MemoryStorage {
    private $data = [];

    public function write($data, $offset, $length) {
        array_splice($this->data, $offset, 0, array_slice($data, 0, $length));
    }

    public function getLength() {
        return count($this->data);
    }
}

class Chunk {
    public $Position;
    public $Timeout;
    public $Storage;
    private $MaxSize;

    public function __construct($start, $size) {
        $this->MaxSize = $size;
        $this->Position = $start;
        $this->Storage = new MemoryStorage();
    }

    public function clear() {
        $this->Position = 0;
        $this->Timeout = 0;
        if ($this->Storage) {
            $this->Storage = new MemoryStorage();
        }
    }

    public function isDownloadCompleted() {
        return $this->Position >= $this->MaxSize;
    }

    public function isValidPosition() {
        return $this->Position >= 0 && $this->Position < $this->MaxSize;
    }

    public function setValidPosition() {
        if (!$this->isValidPosition()) {
            $this->Position = 0;
        }
    }
}

$chunk = new Chunk(0, 1024);
$chunk->Storage->write([0x0, 0x1, 0x2, 0x3, 0x4], 0, 5);
$chunk->clear();
var_dump($chunk->Storage->getLength()); // Should be 0
$chunk->Position = 1025; // Invalid position
$chunk->setValidPosition(); // Set to valid
var_dump($chunk->Position); // Should be 0
--EXPECT--
