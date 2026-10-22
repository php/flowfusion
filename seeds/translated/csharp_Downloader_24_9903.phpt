--TEST--
Translated seed (inferredbugs-csharp: Downloader/24)
--FILE--
<?php

class Chunk {
    public $Position;
    public $Size;
    public $Storage;

    public function __construct($position, $size) {
        $this->Position = $position;
        $this->Size = $size;
        $this->Storage = null;
    }

    public function Clear() {
        $this->Position = 0;
        if ($this->Storage) {
            $this->Storage->Clear();
        }
    }

    public function IsDownloadCompleted() {
        return $this->Position >= $this->Size && $this->Storage && $this->Storage->GetLength() >= $this->Size;
    }

    public function IsValidPosition() {
        if ($this->Position < 0 || ($this->Storage && $this->Position >= $this->Storage->GetLength())) {
            return false;
        }
        return true;
    }

    public function SetValidPosition() {
        if (!$this->IsValidPosition()) {
            $this->Position = 0;
        }
    }
}

class MemoryStorage {
    private $data = [];

    public function Write($data, $offset, $length) {
        for ($i = 0; $i < $length; $i++) {
            $this->data[$offset + $i] = $data[$i];
        }
    }

    public function GetLength() {
        return count($this->data);
    }

    public function Clear() {
        $this->data = [];
    }
}

function testChunk() {
    $size = 1024;
    $chunk = new Chunk(0, $size);
    $chunk->Storage = new MemoryStorage();

    $chunk->Storage->Write(range(0, 4), 0, 5);
    $chunk->Clear();
    $isDownloadCompleted = $chunk->IsDownloadCompleted();
    $isValidPosition = $chunk->IsValidPosition();
    
    $chunk->Position = $size;
    $chunk->SetValidPosition();

    return [$isDownloadCompleted, $isValidPosition, $chunk->Position];
}

testChunk();
--EXPECT--
