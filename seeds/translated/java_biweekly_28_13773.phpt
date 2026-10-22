--TEST--
Translated seed (inferredbugs-java: biweekly/28)
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
            return "";
        }
        return str_replace(";", "\\;", $property->getValue());
    }

    public function parseText($value) {
        return new TextProperty(str_replace("\\;", ";", $value));
    }
}

$marshaller = new TextPropertyMarshaller();

// Test writeText
$prop1 = new TextProperty("the;text");
echo $marshaller->writeText($prop1) . "\n";

// Test writeText with null
$prop2 = new TextProperty(null);
echo $marshaller->writeText($prop2) . "\n";

// Test parseText
$parsedProp = $marshaller->parseText("the\\;text");
echo $parsedProp->getValue() . "\n";
--EXPECT--
