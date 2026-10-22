--TEST--
Translated seed (inferredbugs-java: biweekly/27)
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
    protected $className;

    public function __construct($className) {
        $this->className = $className;
    }

    public function writeText($prop) {
        return str_replace(';', '\\;', $prop->getValue());
    }
    
    public function parseText($value) {
        return new $this->className(str_replace('\\;', ';', $value));
    }
}

$marshaller = new TextPropertyMarshaller('TextProperty');

$prop1 = new TextProperty("the;text");
$actual1 = $marshaller->writeText($prop1);
$expected1 = "the\\;text";
echo $actual1 === $expected1 ? "Test 1 Passed\n" : "Test 1 Failed\n";

$prop2 = new TextProperty(null);
$actual2 = $marshaller->writeText($prop2);
$expected2 = "";
echo $actual2 === $expected2 ? "Test 2 Passed\n" : "Test 2 Failed\n";

$prop3 = $marshaller->parseText("the\\;text");
$expectedValue3 = "the;text";
echo $prop3->getValue() === $expectedValue3 ? "Test 3 Passed\n" : "Test 3 Failed\n";
--EXPECT--
