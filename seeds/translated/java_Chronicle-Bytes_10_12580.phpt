--TEST--
Translated seed (inferredbugs-java: Chronicle-Bytes/10)
--FILE--
<?php

class MappedFile {
    const DEFAULT_CAPACITY = 1 << 40;
    private $chunkSize;
    private $overlapSize;
    private $stores = [];
    private $closed = false;
    private $capacity;

    public function __construct($chunkSize, $overlapSize, $capacity) {
        $this->chunkSize = $this->mapAlign($chunkSize);
        $this->overlapSize = $this->mapAlign($overlapSize);
        $this->capacity = $capacity;
    }

    public static function create($chunkSize, $overlapSize) {
        return new self($chunkSize, $overlapSize, self::DEFAULT_CAPACITY);
    }

    private function mapAlign($size) {
        return $size; // Simulating alignment
    }

    public function acquireByteStore($position) {
        if ($this->closed) {
            throw new Exception("Closed");
        }
        $chunk = (int)($position / $this->chunkSize);
        while (count($this->stores) <= $chunk) {
            $this->stores[] = null;
        }
        if (isset($this->stores[$chunk])) {
            return $this->stores[$chunk]; // Return existing store if available
        }
        $this->stores[$chunk] = $this->createByteStore($chunk);
        return $this->stores[$chunk];
    }

    private function createByteStore($chunk) {
        return new MappedBytesStore($chunk * $this->chunkSize, $this->chunkSize + $this->overlapSize);
    }

    public function close() {
        $this->closed = true;
        foreach ($this->stores as $store) {
            if ($store !== null) {
                $store->release();
            }
        }
    }
}

class MappedBytesStore {
    private $address;
    private $size;

    public function __construct($address, $size) {
        $this->address = $address;
        $this->size = $size;
    }

    public function release() {
        // Simulate releasing resources
    }
}

function main() {
    $mappedFile = MappedFile::create(1024, 256);
    $mappedBytesStore = $mappedFile->acquireByteStore(0);
    $mappedFile->close();
}

main();
--EXPECT--
