--TEST--
Translated seed (inferredbugs-java: biweekly/20)
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
        return $prop->getValue() === null ? "" : (string)$prop->getValue();
    }

    public function parseText($value) {
        if ($value === "invalid") {
            throw new Exception("Cannot parse input");
        }
        return new IntegerProperty($value === "" ? null : (int)$value);
    }
}

$marshaller = new IntegerPropertyMarshaller();

$prop1 = new IntegerProperty(5);
echo $marshaller->writeText($prop1) . "\n";

$prop2 = new IntegerProperty(null);
echo $marshaller->writeText($prop2) . "\n";

$result = $marshaller->parseText("5");
echo $result->getValue() . "\n";

try {
    $marshaller->parseText("invalid");
} catch (Exception $e) {}

$result = $marshaller->parseText("");
echo $result->getValue() === null ? "null" : $result->getValue() . "\n";
--EXPECT--
