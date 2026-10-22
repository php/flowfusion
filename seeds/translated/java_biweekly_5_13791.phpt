--TEST--
Translated seed (inferredbugs-java: biweekly/5)
--FILE--
<?php

class DateTimePropertyMarshaller {
    public function writeText($prop) {
        if ($prop->getValue() === null) {
            return '';
        }
        return $prop->getValue()->format('Ymd\THis\Z');
    }

    public function parseText($value) {
        if ($value === 'invalid') {
            throw new Exception('Cannot parse');
        }
        $dateTime = DateTime::createFromFormat('Ymd\THis\Z', $value);
        return new DateTimePropertyImpl($dateTime);
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

class DateTimePropertyImpl extends DateTimeProperty {
}

$marshaller = new DateTimePropertyMarshaller();
$dateTime = DateTime::createFromFormat('Y-m-d H:i:s', '2013-06-11 13:43:02');

$prop = new DateTimePropertyImpl($dateTime);
echo $marshaller->writeText($prop) . PHP_EOL; // Expected: 20130611T134302Z

$propNull = new DateTimePropertyImpl(null);
echo $marshaller->writeText($propNull) . PHP_EOL; // Expected: 

$propParsed = $marshaller->parseText('20130611T134302Z');
echo $propParsed->getValue()->format('Y-m-d H:i:s') . PHP_EOL; // Expected: 2013-06-11 13:43:02

try {
    $marshaller->parseText('invalid');
} catch (Exception $e) {
    // Expected: Cannot parse
}
--EXPECT--
