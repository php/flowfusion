--TEST--
Translated seed (inferredbugs-csharp: Downloader/38)
--FILE--
<?php

class Chunk {
    public $Position;
    public $MaxSize;
    public $Storage;

    public function __construct($position, $maxSize) {
        $this->Position = $position;
        $this->MaxSize = $maxSize;
    }

    public function Clear() {
        $this->Position = 0;
        if ($this->Storage) {
            $this->Storage->Clear();
        }
    }

    public function IsDownloadCompleted() {
        return $this->Position >= $this->MaxSize && $this->Storage && $this->Storage->GetLength() === $this->MaxSize;
    }

    public function IsValidPosition() {
        return $this->Position >= 0 && (!$this->Storage || $this->Position < $this->Storage->GetLength());
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
        array_splice($this->data, $offset, $length, array_slice($data, 0, $length));
    }

    public function GetLength() {
        return count($this->data);
    }

    public function Clear() {
        $this->data = [];
    }
}

$testData = range(0, 1023); // Generate ordered bytes

$chunk = new Chunk(0, count($testData) - 1);
$chunk->Storage = new MemoryStorage();
$chunk->Storage->Write($testData, 0, count($testData));

$chunk->Clear();

$isDownloadCompleted = $chunk->IsDownloadCompleted();
$isValidPosition = $chunk->IsValidPosition();
$chunk->SetValidPosition();

echo "Is Download Completed: " . ($isDownloadCompleted ? 'True' : 'False') . PHP_EOL;
echo "Is Valid Position: " . ($isValidPosition ? 'True' : 'False') . PHP_EOL;
echo "Current Position: " . $chunk->Position . PHP_EOL;
--EXPECT--
