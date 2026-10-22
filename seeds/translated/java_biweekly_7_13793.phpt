--TEST--
Translated seed (inferredbugs-java: biweekly/7)
--FILE--
<?php

class DateTimePropertyMarshaller {
    private $className;

    public function __construct($className) {
        $this->className = $className;
    }

    public function writeText($prop) {
        if ($prop->getValue() === null) {
            return "";
        }
        return $prop->getValue()->format("Ymd\THis\Z");
    }

    public function parseText($value) {
        $dateTime = DateTime::createFromFormat('Ymd\THis\Z', $value);
        if (!$dateTime) {
            throw new Exception("Cannot parse date");
        }
        return new $this->className($dateTime);
    }
}

class DateTimeProperty {
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }
}

$marshaller = new DateTimePropertyMarshaller('DateTimePropertyImpl');

$datetime = new DateTime("2013-06-11 13:43:02", new DateTimeZone("UTC"));
$prop = new DateTimePropertyImpl($datetime);

$actual = $marshaller->writeText($prop);
$expected = "20130611T134302Z";
if ($actual !== $expected) {
    echo "Failed: expected $expected, got $actual\n";
}

$propNull = new DateTimePropertyImpl(null);
$actualNull = $marshaller->writeText($propNull);
$expectedNull = "";
if ($actualNull !== $expectedNull) {
    echo "Failed: expected $expectedNull, got $actualNull\n";
}

$result = $marshaller->parseText("20130611T134302Z");
if ($result->getValue() != $datetime) {
    echo "Failed: parsed date does not match\n";
}

try {
    $marshaller->parseText("invalid");
} catch (Exception $e) {
    // Expected exception
}
  
class DateTimePropertyImpl extends DateTimeProperty {}
--EXPECT--
