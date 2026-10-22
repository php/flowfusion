--TEST--
Translated seed (inferredbugs-java: biweekly/21)
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
    public function writeText($prop) {
        return $prop->getValue() !== null ? (string)$prop->getValue() : '';
    }

    public function parseText($value) {
        if ($value === "invalid") {
            throw new Exception("Cannot parse");
        }
        return new IntegerProperty($value !== '' ? (int)$value : null);
    }
}

$marshaller = new IntegerPropertyMarshaller();

$prop1 = new IntegerProperty(5);
echo $marshaller->writeText($prop1) . PHP_EOL; // Outputs: 5

$prop2 = new IntegerProperty(null);
echo $marshaller->writeText($prop2) . PHP_EOL; // Outputs: 

$prop3 = $marshaller->parseText("5");
echo $prop3->getValue() . PHP_EOL; // Outputs: 5

try {
    $marshaller->parseText("invalid");
} catch (Exception $e) {
    // Handle exception
}

$prop4 = $marshaller->parseText("");
echo $prop4->getValue() === null ? 'null' : $prop4->getValue(); // Outputs: null
--EXPECT--
