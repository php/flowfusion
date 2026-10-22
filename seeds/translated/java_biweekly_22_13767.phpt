--TEST--
Translated seed (inferredbugs-java: biweekly/22)
--FILE--
<?php

class IntegerProperty {
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }
}

class IntegerPropertyMarshaller {
    public function writeText(IntegerProperty $prop) {
        return $prop->getValue() !== null ? (string)$prop->getValue() : '';
    }

    public function parseText($value) {
        if (!is_numeric($value) && $value !== '') {
            throw new Exception("Cannot parse exception");
        }
        return new IntegerProperty($value !== '' ? (int)$value : null);
    }
}

$marshaller = new IntegerPropertyMarshaller();

// Test writeText
$prop1 = new IntegerProperty(5);
echo $marshaller->writeText($prop1) . "\n";

// Test writeText null
$prop2 = new IntegerProperty(null);
echo $marshaller->writeText($prop2) . "\n";

// Test parseText valid
$result1 = $marshaller->parseText("5");
echo $result1->getValue() . "\n";

// Test parseText invalid
try {
    $marshaller->parseText("invalid");
} catch (Exception $e) {
}

// Test parseText empty
$result2 = $marshaller->parseText("");
echo $result2->getValue() . "\n";
--EXPECT--
