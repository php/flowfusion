--TEST--
Translated seed (inferredbugs-csharp: Downloader/33)
--FILE--
<?php

class MemoryStorage {
    private $data = [];
    
    public function write(array $bytes, $offset, $length) {
        for ($i = 0; $i < $length; $i++) {
            $this->data[$offset + $i] = $bytes[$i];
        }
    }

    public function getLength() {
        return count($this->data);
    }
}

class Chunk {
    public $Position = 0;
    public $Timeout = 0;
    private $start;
    private $end;
    public $Storage;

    public function __construct($start, $end) {
        $this->start = $start;
        $this->end = $end;
    }

    public function clear() {
        $this->Position = 0;
        $this->Timeout = 0;
        if ($this->Storage) {
            $this->Storage->write([], 0, $this->Storage->getLength());
        }
    }

    public function isDownloadCompleted() {
        return $this->Position >= $this->end;
    }

    public function isValidPosition() {
        return $this->Position >= 0 && $this->Position < $this->Storage->getLength();
    }

    public function setValidPosition() {
        if (!$this->isValidPosition()) {
            $this->Position = 0;
        }
    }
}

function main() {
    $size = 1024;
    $chunk = new Chunk(0, $size - 1);
    $chunk->Storage = new MemoryStorage();
    
    // Act
    $chunk->clear();
    
    // Validate internal state
    echo $chunk->Position; // Expected: 0
    echo "\n";
    echo $chunk->Timeout; // Expected: 0
    echo "\n";
    
    $chunk->Storage->write(range(0, 4), 0, 5);
    $chunk->clear();
    echo $chunk->Storage->getLength(); // Expected: 0
    echo "\n";
    
    $chunk->Position = $size;
    $chunk->setValidPosition();
    echo $chunk->Position; // Expected: 0
}

main();
--EXPECT--
