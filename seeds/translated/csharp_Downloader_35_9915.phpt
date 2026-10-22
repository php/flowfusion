--TEST--
Translated seed (inferredbugs-csharp: Downloader/35)
--FILE--
<?php

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

    public function Clear() {
        $this->Position = 0;
        $this->Timeout = 0;
        if ($this->Storage !== null) {
            $this->Storage->Clear();
        }
    }

    public function IsDownloadCompleted() {
        return $this->Position >= ($this->end - $this->start);
    }

    public function IsValidPosition() {
        return $this->Position >= 0 && $this->Position < $this->end;
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

    public function Clear() {
        $this->data = [];
    }

    public function GetLength() {
        return count($this->data);
    }
}

$size = 1024;
$chunk = new Chunk(0, $size);
$chunk->Storage = new MemoryStorage();
$chunk->Storage->Write(array(0x0, 0x1, 0x2, 0x3, 0x4), 0, 5);

$chunk->Clear();

$downloadCompleted = $chunk->IsDownloadCompleted();
$validPosition = $chunk->IsValidPosition();
$chunk->SetValidPosition();

echo "Download Completed: " . ($downloadCompleted ? "true" : "false") . "\n";
echo "Valid Position: " . ($validPosition ? "true" : "false") . "\n";
echo "Position after SetValidPosition: " . $chunk->Position . "\n";
--EXPECT--
