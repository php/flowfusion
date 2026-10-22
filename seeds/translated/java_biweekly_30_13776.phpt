--TEST--
Translated seed (inferredbugs-java: biweekly/30)
--FILE--
<?php

class ICalProperty {
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }
}

class TestProperty extends ICalProperty {}

class ICalendar {
    private $properties = [];
    private $productId;

    public function setProductId($productId) {
        $this->productId = $productId;
    }

    public function addProperty($property) {
        $this->properties[] = $property;
    }

    public function getProperties() {
        return $this->properties;
    }
}

class JCalWriter {
    private $output;

    public function __construct($output) {
        $this->output = $output;
    }

    public function write($icalendar) {
        $this->output[] = "[" . json_encode(["vcalendar", [["version", [], "text", "2.0"], ["prodid", [], "text", $icalendar->productId]]]) . "]";
    }

    public function close() {}
}

$output = [];
$ical = new ICalendar();
$ical->setProductId("prodid");
$ical->addProperty(new TestProperty("value"));

$writer = new JCalWriter($output);
$writer->write($ical);
$writer->close();

$actual = implode("", $output);
$expected = '[["vcalendar",[["version",{}, "text","2.0"],["prodid",{}, "text","prodid"]]]];';
--EXPECT--
