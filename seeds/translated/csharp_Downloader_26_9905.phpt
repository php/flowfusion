--TEST--
Translated seed (inferredbugs-csharp: Downloader/26)
--FILE--
<?php

class MemoryStorage {
    private $data = [];

    public function write($data, $offset, $length) {
        $this->data = array_merge($this->data, array_slice($data, $offset, $length));
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
        if ($this->Storage) {
            $this->Storage->write([], 0, $this->Storage->getLength());
        }
    }

    public function isDownloadCompleted() {
        return $this->Position >= $this->MaxSize;
    }

    public function isValidPosition() {
        if ($this->Storage) {
            return $this->Position >= 0 && $this->Position < $this->Storage->getLength();
        }
        return $this->Position == 0;
    }

    public function setValidPosition() {
        if (!$this->isValidPosition()) {
            $this->Position = 0;
        }
    }
}

$chunk = new Chunk(0, 1024);
$chunk->Storage = new MemoryStorage();
$chunk->Storage->write([0x0, 0x1, 0x2, 0x3, 0x4], 0, 5);
$chunk->clear();

echo $chunk->Storage->getLength(); // should output 0

$chunk->Position = 1025; // Overflow test
$chunk->setValidPosition();
echo $chunk->Position; // should output 0
--EXPECT--
