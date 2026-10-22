--TEST--
Translated seed (inferredbugs-java: bt/22)
--FILE--
<?php

class BEParser {
    private $data;
    private $pos;

    public function __construct($data) {
        if ($data === "") {
            throw new Exception("Empty string");
        }
        if (preg_match('/^0:/', $data)) {
            throw new Exception("Length starts with zero");
        }
        $this->data = $data;
        $this->pos = 0;
    }

    public function readType() {
        $char = $this->data[$this->pos];
        if ($char === 'i') {
            return 'integer';
        } elseif ($char === 'l') {
            return 'list';
        } elseif ($char === 'd') {
            return 'map';
        } elseif (is_numeric($char)) {
            return 'string';
        }
        throw new Exception("Unexpected type");
    }

    public function readString() {
        if (preg_match('/^(\d+):/', substr($this->data, $this->pos), $matches)) {
            $length = (int)$matches[1];
            $this->pos += strlen($matches[0]);
            if ($this->pos + $length > strlen($this->data)) {
                throw new Exception("Insufficient content");
            }
            $string = substr($this->data, $this->pos, $length);
            $this->pos += $length;
            return $string;
        }
        throw new Exception("Invalid string format");
    }

    public function readInteger() {
        if (substr($this->data, $this->pos, 2) === 'ie') {
            throw new Exception("Zero length integer");
        }
        if (preg_match('/^i(-?\d+)e/', substr($this->data, $this->pos), $matches)) {
            $this->pos += strlen($matches[0]);
            return (int)$matches[1];
        }
        throw new Exception("Invalid integer format");
    }
}

$parser = new BEParser("ie");
$parser->readType();
$parser->readInteger();
--EXPECT--
