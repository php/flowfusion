--TEST--
Translated seed (inferredbugs-java: biweekly/18)
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

class IntegerPropertyMarshallerImpl {
    public function writeText($prop) {
        return $prop->getValue() === null ? "" : (string)$prop->getValue();
    }

    public function parseText($value) {
        if ($value === "invalid") {
            throw new Exception("Cannot parse");
        }
        return new IntegerProperty($value === "" ? null : (int)$value);
    }
}

$marshaller = new IntegerPropertyMarshallerImpl();

// Test writeText
$prop = new IntegerProperty(5);
echo $marshaller->writeText($prop) . "\n"; // Should output: 5

// Test writeText with null
$prop = new IntegerProperty(null);
echo $marshaller->writeText($prop) . "\n"; // Should output: (empty string)

// Test parseText
$prop = $marshaller->parseText("5");
echo $prop->getValue() . "\n"; // Should output: 5

// Test parseText with invalid input
try {
    $marshaller->parseText("invalid");
} catch (Exception $e) {
    echo $e->getMessage() . "\n"; // Should output: Cannot parse
}

// Test parseText with empty string
$prop = $marshaller->parseText("");
echo $prop->getValue() . "\n"; // Should output: (empty string)
--EXPECT--
