--TEST--
Translated seed (inferredbugs-java: commons-codec/29)
--FILE--
<?php

class Base32InputStream {
    private $stream;
    private $decodeMode;
    
    public function __construct($stream, $decodeMode = false) {
        $this->stream = $stream;
        $this->decodeMode = $decodeMode;
    }

    public function read() {
        return $this->stream->read();
    }

    public function skip($n) {
        if ($n < 0) {
            throw new InvalidArgumentException();
        }
        return $this->stream->skip($n);
    }
}

class Codec105ErrorInputStream {
    private $position = 0;

    public function read() {
        if ($this->position < 5) {
            $this->position++;
            return 0; // simulate returning 0 bytes after a few reads
        }
        return -1; // end of stream
    }
}

$encodedFoo = "MZXW6===";
$stream = new Base32InputStream(new Codec105ErrorInputStream(), true);
for ($i = 0; $i < 5; $i++) {
    $stream->read();
}
$stream->skip(10);
--EXPECT--
