--TEST--
Translated seed (inferredbugs-java: TwelveMonkeys/10)
--FILE--
<?php

class JPEGSegmentImageInputStream {
    private $stream;
    private $position = 0;

    public function __construct($stream) {
        if ($stream === null) {
            throw new InvalidArgumentException();
        }
        $this->stream = $stream;
    }

    public function read() {
        if ($this->position >= strlen($this->stream)) {
            return -1;
        }
        return ord($this->stream[$this->position++]);
    }

    public function readFully(&$buffer) {
        for ($i = 0; $i < count($buffer); $i++) {
            $byte = $this->read();
            if ($byte === -1) {
                return $i;
            }
            $buffer[$i] = $byte;
        }
        return count($buffer);
    }

    public function seek($position) {
        $this->position = $position;
    }

    public function getStreamPosition() {
        return $this->position;
    }
}

$stream1 = new JPEGSegmentImageInputStream("\x2A\x2A\x00\x00\x4D\x63");
$stream1->read();
$stream1->read();

$stream2 = new JPEGSegmentImageInputStream("");
$stream2->read();

$stream3 = new JPEGSegmentImageInputStream("");
$buffer = array(0);
$stream3->readFully($buffer);

$stream4 = new JPEGSegmentImageInputStream("valid data");
$length = 0;
while ($stream4->read() != -1) {
    $length++;
}

$stream5 = new JPEGSegmentImageInputStream("data with padding");
$buffer2 = array_fill(0, 20, 0);
$stream5->readFully($buffer2);

$stream6 = new JPEGSegmentImageInputStream("data with invalid segment");
$stream6->seek(0x2012);
$stream6->read($buffer2);
$stream6->read($buffer2);
--EXPECT--
