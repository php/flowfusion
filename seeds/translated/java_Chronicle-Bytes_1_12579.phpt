--TEST--
Translated seed (inferredbugs-java: Chronicle-Bytes/1)
--FILE--
<?php

class NativeBytes {
    const MAX_CAPACITY = PHP_INT_MAX;
    private $data;
    private $position;

    public function __construct($initialCapacity) {
        $this->data = str_repeat("\0", $initialCapacity);
        $this->position = 0;
    }

    public static function nativeBytes($initialCapacity = 1024) {
        return new self($initialCapacity);
    }

    public function capacity() {
        return strlen($this->data);
    }

    public function write($str, $offset, $length) {
        $this->ensureCapacity($this->position + $length);
        $this->data = substr_replace($this->data, $str, $this->position + $offset, $length);
        $this->position += $length;
    }

    public function ensureCapacity($size) {
        if ($size > $this->capacity()) {
            $this->checkResize($size);
        }
    }

    private function checkResize($endOfBuffer) {
        if ($endOfBuffer < 0) {
            throw new InvalidArgumentException();
        }
        
        $newSize = max($endOfBuffer, $this->capacity() * 3 / 2);
        $this->data = str_pad($this->data, $newSize, "\0");
    }

    public function writeVolatileInt($offset, $value) {
        $this->write(pack('L', $value), $offset, 4);
    }

    public function writeLong($offset, $value) {
        $this->write(pack('P', $value), $offset, 8);
    }
}

$bytes = NativeBytes::nativeBytes(10);
$bytes->write("Hello", 0, 5);
$bytes->writeVolatileInt(5, 1234);
$bytes->writeLong(9, 123456789);
$bytes->write("World", 17, 5); // This will trigger a resize due to capacity issues.
--EXPECT--
