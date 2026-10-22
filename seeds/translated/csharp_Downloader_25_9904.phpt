--TEST--
Translated seed (inferredbugs-csharp: Downloader/25)
--FILE--
<?php

class MemoryStorage {
    private $data = [];

    public function write($bytes) {
        $this->data = $bytes;
    }

    public function getLength() {
        return count($this->data);
    }
}

class Chunk {
    public $Position = 0;
    public $Timeout = 0;
    public $Storage;
    private $start;
    private $end;

    public function __construct($start, $end) {
        $this->start = $start;
        $this->end = $end;
    }

    public function clear() {
        $this->Position = 0;
        $this->Timeout = 0;
        if ($this->Storage) {
            $this->Storage->write([]);
        }
    }

    public function isDownloadCompleted() {
        return $this->Position >= $this->end - 1 && $this->Storage->getLength() > 0;
    }

    public function isValidPosition() {
        return $this->Position >= 0 && $this->Position <= $this->end;
    }

    public function setValidPosition() {
        if (!$this->isValidPosition()) {
            $this->Position = 0;
        }
    }
}

function main() {
    $size = 1024;
    $chunk = new Chunk(0, $size);
    $chunk->Storage = new MemoryStorage();

    // Clear test
    $chunk->clear();

    // Validate position when no storage
    var_dump($chunk->isValidPosition()); // Expected: true

    // Set valid position on overflow
    $chunk->Position = $size;
    $chunk->setValidPosition();
    var_dump($chunk->Position); // Expected: 0

    // Download completed when no data
    var_dump($chunk->isDownloadCompleted()); // Expected: false

    // Valid position when negative
    $chunk->Position = -1;
    var_dump($chunk->isValidPosition()); // Expected: false

    // Write data and test download complete
    $chunk->Storage->write(array_fill(0, $size, 0x0));
    $chunk->Position = $size - 1;
    var_dump($chunk->isDownloadCompleted()); // Expected: true
}

main();
--EXPECT--
