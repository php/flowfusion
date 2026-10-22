--TEST--
Translated seed (inferredbugs-java: commons-imaging/4)
--FILE--
<?php

class TiffField
{
    public $tag;
    public $directoryType;
    public $type;
    public $length;
    public $valueOffset;
    public $valueOffsetBytes;
    public $oversizeValue = null;
    public $byteOrder;

    public function __construct($tag, $directoryType, $type, $length, $valueOffset, $valueOffsetBytes, $byteOrder)
    {
        $this->tag = $tag;
        $this->directoryType = $directoryType;
        $this->type = $type;
        $this->length = $length;
        $this->valueOffset = $valueOffset;
        $this->valueOffsetBytes = $valueOffsetBytes;
        $this->byteOrder = $byteOrder;
    }

    public function setOversizeValue($bytes)
    {
        $this->oversizeValue = $bytes;
    }

    public function fillInValue($byteSource)
    {
        if ($this->isLocalValue()) return;
        $valueLength = $this->getValueLengthInBytes();
        $bytes = $byteSource->getBlock($this->valueOffset, $valueLength);
        $this->setOversizeValue($bytes);
    }

    public function isLocalValue()
    {
        return $this->type == 0; // Simplified for demonstration
    }

    public function getValueLengthInBytes()
    {
        return $this->length * 4; // Assuming a unit length of 4 bytes for simplicity
    }
}

class ByteSource
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getBlock($offset, $length)
    {
        return array_slice($this->data, $offset, $length);
    }
}

$data = range(0, 100); // Simulate data
$byteSource = new ByteSource($data);

$tiffField = new TiffField(1, 2, 3, 10, 0, [], 1);
$tiffField->fillInValue($byteSource);

print_r($tiffField->oversizeValue);
--EXPECT--
