--TEST--
Translated seed (inferredbugs-java: Java-Lang/26)
--FILE--
<?php

class Bytes {
    const END_OF_BUFFER = -1;
    const UNSIGNED_INT_MASK = 0xFFFFFFFF;

    private $buffer;
    private $position = 0;

    public function __construct($size) {
        $this->buffer = array_fill(0, $size, 0);
    }

    public function write($value) {
        $this->buffer[$this->position++] = $value & 0xFF; // Store as unsigned byte
    }

    public function readUnsignedByte() {
        return $this->buffer[$this->position++] & 0xFF;
    }

    public function remaining() {
        return count($this->buffer) - $this->position;
    }

    public function reset() {
        $this->position = 0;
    }

    public function readUTF() {
        $result = "";
        while ($this->remaining() > 0) {
            $c = $this->readUnsignedByte();
            // Check for UTF-8 multi-byte sequences
            if ($c < 0x80) {
                $result .= chr($c);
            } elseif (($c >> 5) === 0x6) {
                $char2 = $this->readUnsignedByte();
                $result .= chr((($c & 0x1F) << 6) | ($char2 & 0x3F));
            } elseif (($c >> 4) === 0xE) {
                $char2 = $this->readUnsignedByte();
                $char3 = $this->readUnsignedByte();
                $result .= chr((($c & 0x0F) << 12) | (($char2 & 0x3F) << 6) | ($char3 & 0x3F));
            } else {
                throw new Exception("Malformed input");
            }
        }
        return $result;
    }
}

$bytes = new Bytes(100);
$bytes->write(0x61); // 'a'
$bytes->write(0xC2); // Start of UTF-8 sequence
$bytes->write(0xA9); // '©'
$bytes->reset();
echo $bytes->readUTF(); // Output: a©
--EXPECT--
