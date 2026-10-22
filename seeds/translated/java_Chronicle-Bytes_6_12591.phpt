--TEST--
Translated seed (inferredbugs-java: Chronicle-Bytes/6)
--FILE--
<?php

class Bytes {
    private $data;
    private $position;
    private $limit;

    public function __construct($size) {
        $this->data = array_fill(0, $size, 0);
        $this->position = 0;
        $this->limit = $size;
    }

    public function writeLong($offset, $value) {
        foreach (array_reverse(unpack('C*', pack('P', $value))) as $byte) {
            if ($offset < $this->limit) {
                $this->data[$offset++] = $byte;
            }
        }
    }

    public function readLong($offset) {
        return unpack('P', pack('C*', ...array_slice($this->data, $offset, 8)))[1];
    }

    public function readPosition($position) {
        $this->position = $position;
    }

    public function limit() {
        return $this->limit;
    }

    public function readLimit($limit) {
        $this->limit = $limit;
    }
}

function main() {
    $bytes = new Bytes(30);
    
    $expected = 12345;
    $offset = 5;

    $bytes->writeLong($offset, $expected);
    $bytes->readPosition($offset + 8);

    $result = $bytes->readLong($offset);
    // Output the result to see if it matches the expected value
    echo $result === $expected ? "Success: $result\n" : "Failed: $result\n";
}

main();
--EXPECT--
