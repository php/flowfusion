--TEST--
Translated seed (inferredbugs-csharp: Downloader/28)
--FILE--
<?php

class MemoryStorage {
    private $data = [];

    public function writeAsync($data, $offset, $length) {
        array_splice($this->data, $offset, $length, array_slice($data, 0, $length));
    }

    public function getLength() {
        return count($this->data);
    }
}

class Chunk {
    public $Position;
    public $Timeout;
    public $Storage;

    private $start;
    private $end;

    public function __construct($start, $end) {
        $this->start = $start;
        $this->end = $end;
        $this->Position = 0;
        $this->Timeout = 0;
    }

    public function clear() {
        $this->Position = 0;
        $this->Timeout = 0;
        $this->Storage = null;
    }

    public function isDownloadCompleted() {
        return $this->Position >= $this->end;
    }

    public function isValidPosition() {
        return $this->Position >= $this->start && $this->Position < $this->end;
    }

    public function setValidPosition() {
        if (!$this->isValidPosition()) {
            $this->Position = 0;
        }
    }
}

$chunk = new Chunk(0, 1024);
$chunk->Storage = new MemoryStorage();
$data = [0x0, 0x1, 0x2, 0x3, 0x4];
$chunk->Storage->writeAsync($data, 0, count($data));
$chunk->clear();

$chunk->Position = 1024; // Setting position for validation
$chunk->setValidPosition();

$validPosition = $chunk->isValidPosition();
$isCompleted = $chunk->isDownloadCompleted();
--EXPECT--
