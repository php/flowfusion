--TEST--
Translated seed (inferredbugs-java: biweekly/26)
--FILE--
<?php

class TextProperty {
    private $value;
    
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
}

class TextPropertyMarshaller {
    public function writeText($property) {
        if ($property->getValue() === null) {
            return '';
        }
        return str_replace(';', '\\;', $property->getValue());
    }

    public function parseText($value) {
        return new TextProperty(str_replace('\\;', ';', $value));
    }
}

function main() {
    $marshaller = new TextPropertyMarshaller();

    $prop1 = new TextProperty("the;text");
    $actual1 = $marshaller->writeText($prop1);
    $expected1 = "the\\;text";
    echo ($actual1 === $expected1 ? "Test 1 passed\n" : "Test 1 failed\n");

    $prop2 = new TextProperty(null);
    $actual2 = $marshaller->writeText($prop2);
    $expected2 = "";
    echo ($actual2 === $expected2 ? "Test 2 passed\n" : "Test 2 failed\n");

    $value3 = "the\\;text";
    $prop3 = $marshaller->parseText($value3);
    $actual3 = $prop3->getValue();
    $expected3 = "the;text";
    echo ($actual3 === $expected3 ? "Test 3 passed\n" : "Test 3 failed\n");
}

main();
--EXPECT--
