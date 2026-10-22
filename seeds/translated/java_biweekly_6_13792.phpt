--TEST--
Translated seed (inferredbugs-java: biweekly/6)
--FILE--
<?php

class DateTimePropertyMarshaller {
    public function writeText($prop) {
        if ($prop === null || $prop->getValue() === null) {
            return '';
        }
        return $prop->getValue()->format('Ymd\THis\Z');
    }

    public function parseText($value) {
        if ($value === "invalid") {
            throw new Exception("Cannot parse");
        }
        $date = DateTime::createFromFormat('Ymd\THis\Z', $value);
        return new DateTimePropertyImpl($date);
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

class DateTimePropertyImpl extends DateTimeProperty {}

$marshaller = new DateTimePropertyMarshaller();

$c = new DateTime("2013-06-11 13:43:02", new DateTimeZone("UTC"));
$prop = new DateTimePropertyImpl($c);

$actual = $marshaller->writeText($prop);
$expected = "20130611T134302Z";
echo $actual === $expected ? "Success" : "Fail";

$propNull = new DateTimePropertyImpl(null);
$actualNull = $marshaller->writeText($propNull);
$expectedNull = "";
echo $actualNull === $expectedNull ? " Success" : " Fail";

$parsedProp = $marshaller->parseText("20130611T134302Z");
echo $parsedProp->getValue() == $c ? " Success" : " Fail";

try {
    $marshaller->parseText("invalid");
} catch (Exception $e) {
    echo "Success";
}
--EXPECT--
