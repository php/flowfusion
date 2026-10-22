--TEST--
Translated seed (inferredbugs-java: Chronicle-Bytes/14)
--FILE--
<?php

class MappedMemory {
    private $data;
    private $capacity;
    private $writePosition;

    public function __construct($capacity) {
        $this->capacity = $capacity;
        $this->data = str_repeat("\0", $capacity);
        $this->writePosition = 0;
    }

    public function writeInt($offset, $value) {
        if ($offset < 0 || $offset + 4 > $this->capacity) {
            throw new RuntimeException("Buffer overflow at offset $offset");
        }
        $this->data[$offset] = pack('V', $value); // 32-bit unsigned integer
        $this->writePosition = max($this->writePosition, $offset + 4);
    }

    public function writeUtf8($string) {
        $length = strlen($string);
        if ($this->writePosition + $length > $this->capacity) {
            throw new RuntimeException("Buffer overflow while writing string");
        }
        for ($i = 0; $i < $length; $i++) {
            $this->data[$this->writePosition++] = $string[$i];
        }
    }

    public function peekVolatileInt($offset) {
        if ($offset < 0 || $offset + 4 > $this->capacity) {
            throw new RuntimeException("Buffer underflow at offset $offset");
        }
        return unpack('V', substr($this->data, $offset, 4))[1];
    }

    public function writeCheckOffset($offset, $adding) {
        if ($offset < 0 || $offset + $adding > $this->capacity) {
            throw new RuntimeException("Offset out of bounds: $offset");
        }
    }
}

function main() {
    $mappedMemory = new MappedMemory(1024);

    // Triggering potential buffer overflow by writing beyond capacity
    try {
        $mappedMemory->writeInt(1020, 42); // Should succeed
        $mappedMemory->writeInt(1025, 43); // Should throw an error
    } catch (RuntimeException $e) {
        echo "Caught exception: " . $e->getMessage() . "\n";
    }

    // Normal operation
    $mappedMemory->writeUtf8("Hello");
    echo "Value at offset 0: " . $mappedMemory->peekVolatileInt(0) . "\n";
}

main();
--EXPECT--
