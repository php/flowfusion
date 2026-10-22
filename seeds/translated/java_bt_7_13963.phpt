--TEST--
Translated seed (inferredbugs-java: bt/7)
--FILE--
<?php

class BEParser {
    private $data;
    private $position;

    public function __construct($data) {
        if (empty($data) || preg_match('/^0:/', $data) || preg_match('/^\d+:$/', $data)) {
            throw new Exception("Invalid input");
        }
        $this->data = $data;
        $this->position = 0;
    }

    public function readType() {
        $char = $this->data[$this->position];
        if ($char === 'i') return 'integer';
        if ($char === 'l') return 'list';
        if ($char === 'd') return 'map';
        if (is_numeric($char)) return 'string';
        throw new Exception("Unexpected type");
    }

    public function readString() {
        $colon = strpos($this->data, ':', $this->position);
        $length = intval(substr($this->data, $this->position, $colon - $this->position));
        $this->position = $colon + 1;
        if (strlen($this->data) < $this->position + $length) throw new Exception("Insufficient content");
        $string = substr($this->data, $this->position, $length);
        $this->position += $length;
        return $string;
    }

    public function readInteger() {
        if ($this->data[$this->position + 1] !== 'e') throw new Exception("Not terminated");
        $this->position += 2; // skip 'i' and 'e'
        return intval(substr($this->data, $this->position - 2, 1));
    }
}

function run() {
    $parser = new BEParser("i-1e");
    echo $parser->readType(), "\n"; // integer
    echo $parser->readInteger(), "\n"; // -1
}

run();
--EXPECT--
