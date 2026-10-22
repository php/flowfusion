--TEST--
Translated seed (inferredbugs-java: UA-Java-Legacy/2)
--FILE--
<?php

class EncoderCalc {
    private $length = 0;

    public function getLength() {
        return $this->length;
    }

    public function reset() {
        $this->length = 0;
    }

    public function putBoolean($fieldName, $v) {
        $this->length++;
    }

    public function putBooleanArray($fieldName, $v) {
        if ($v === null) {
            $this->length += 4;
            return;
        }
        $this->length += 4 + count($v);
    }

    public function putInt32($fieldName, $v) {
        $this->length += 4;
    }

    public function putInt32Array($fieldName, $v) {
        if ($v === null) {
            $this->length += 4;
            return;
        }
        $this->length += 4 + 4 * count($v);
    }

    public function putString($fieldName, $v) {
        if ($v === null) {
            $this->length += 4;
        } else {
            $this->length += 4 + strlen($v);
        }
    }

    public function putDateTime($fieldName, $v) {
        $this->length += 8;
    }

    public function putDateTimeArray($fieldName, $v) {
        if ($v === null) {
            $this->length += 4;
            return;
        }
        $this->length += 4 + 8 * count($v);
    }
}

$encoder = new EncoderCalc();
$encoder->putBoolean("testBool", true);
$encoder->putInt32("testInt", 123);
$encoder->putString("testString", "Hello World");
$encoder->putDateTime("testDateTime", time());
$encoder->putBooleanArray("testBoolArray", [true, false, true]);
$encoder->putInt32Array("testIntArray", [1, 2, 3, 4]);
$encoder->putDateTimeArray("testDateTimeArray", [time(), time() + 3600]);

$length = $encoder->getLength();
echo $length;
--EXPECT--
