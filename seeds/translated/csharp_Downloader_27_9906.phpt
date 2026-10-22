--TEST--
Translated seed (inferredbugs-csharp: Downloader/27)
--FILE--
<?php

class Chunk {
    public $Position;
    public $Timeout;
    public $Start;
    public $End;
    public $Storage;

    public function __construct($start, $end) {
        $this->Start = $start;
        $this->End = $end;
        $this->Position = 0;
        $this->Timeout = 0;
    }

    public function Clear() {
        $this->Position = 0;
        $this->Timeout = 0;
        if ($this->Storage) {
            $this->Storage->Clear();
        }
    }

    public function IsDownloadCompleted() {
        return $this->Position >= ($this->End - $this->Start);
    }

    public function IsValidPosition() {
        return $this->Position >= 0 && $this->Position < ($this->End - $this->Start);
    }

    public function SetValidPosition() {
        if (!$this->IsValidPosition()) {
            $this->Position = 0;
        }
    }
}

class MemoryStorage {
    private $data = [];

    public function Write($data, $offset) {
        $this->data = array_slice($data, 0, $offset);
    }

    public function GetLength() {
        return count($this->data);
    }

    public function Clear() {
        $this->data = [];
    }
}

function testClear() {
    $chunk = new Chunk(0, 1000);
    $chunk->Position = 100;
    $chunk->Timeout = 100;
    $chunk->Clear();
    echo $chunk->Position === 0 ? "Position cleared\n" : "Position not cleared\n";
    echo $chunk->Timeout === 0 ? "Timeout cleared\n" : "Timeout not cleared\n";
}

function testIsValidPositionOnOverflow() {
    $chunk = new Chunk(0, 1024);
    $chunk->Position = 1024;
    $chunk->SetValidPosition();
    echo $chunk->Position === 0 ? "Position reset on overflow\n" : "Position not reset on overflow\n";
}

function main() {
    testClear();
    testIsValidPositionOnOverflow();
}

main();
--EXPECT--
