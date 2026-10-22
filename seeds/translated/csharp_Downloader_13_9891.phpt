--TEST--
Translated seed (inferredbugs-csharp: Downloader/13)
--FILE--
<?php

class Chunk {
    public $Position;
    public $Timeout;
    public $FailoverCount;
    public $Storage;
    private $start;
    private $size;

    public function __construct($start, $size) {
        $this->start = $start;
        $this->size = $size;
        $this->Position = 0;
        $this->Timeout = 0;
        $this->FailoverCount = 0;
    }

    public function Clear() {
        $this->Position = 0;
        $this->Timeout = 0;
        $this->FailoverCount = 0;
        if ($this->Storage) {
            $this->Storage->Clear();
        }
    }

    public function IsDownloadCompleted() {
        return $this->Position >= $this->size - 1 && $this->Storage && $this->Storage->GetLength() > 0;
    }

    public function IsValidPosition() {
        return $this->Position >= 0 && ($this->Storage ? $this->Position < $this->Storage->GetLength() : $this->Position == 0);
    }

    public function SetValidPosition() {
        if (!$this->IsValidPosition()) {
            $this->Position = 0;
        }
    }
}

class MemoryStorage {
    private $data = [];

    public function Write($data, $offset = 0, $length = null) {
        if ($length === null) {
            $length = count($data);
        }
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

$size = 1024;
$chunk = new Chunk(0, $size - 1);
$chunk->Storage = new MemoryStorage();
$chunk->Position = $size; // overflowed
$chunk->SetValidPosition();

$chunk->Clear();

$chunk->Position = 1;
$chunk->SetValidPosition();
--EXPECT--
