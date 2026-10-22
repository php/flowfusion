--TEST--
Translated seed (inferredbugs-java: biweekly/19)
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
        if ($value === '') {
            return new IntegerProperty(null);
        }
        if (!is_numeric($value)) {
            throw new Exception("Cannot parse: $value");
        }
        return new IntegerProperty((int)$value);
    }
}

$marshaller = new IntegerPropertyMarshaller();

$prop1 = new IntegerProperty(5);
echo $marshaller->writeText($prop1) . PHP_EOL;

$prop2 = new IntegerProperty(null);
echo $marshaller->writeText($prop2) . PHP_EOL;

$result1 = $marshaller->parseText("5");
echo $result1->getValue() . PHP_EOL;

try {
    $marshaller->parseText("invalid");
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

$result2 = $marshaller->parseText("");
echo $result2->getValue() . PHP_EOL;

?>
--EXPECT--
