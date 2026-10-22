--TEST--
Translated seed (inferredbugs-java: biweekly/34)
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

class ICalWriter {
    private $output;
    private $warnings = [];

    public function __construct($output) {
        $this->output = $output;
    }

    public function write($calendar) {
        fwrite($this->output, "BEGIN:VCALENDAR\r\n");
        fwrite($this->output, "VERSION:2.0\r\n");
        fwrite($this->output, "PRODID:.*?\r\n");
        foreach ($calendar->getProperties() as $property) {
            fwrite($this->output, $property->getValue() . "\r\n");
        }
        fwrite($this->output, "END:VCALENDAR\r\n");
    }

    public function close() {
        fclose($this->output);
    }

    public function getWarnings() {
        return $this->warnings;
    }

    public function registerMarshaller($marshaller) {
        // Simulating registration
    }
}

class ICalendar {
    private $properties = [];

    public function addProperty($property) {
        $this->properties[] = $property;
    }

    public function getProperties() {
        return $this->properties;
    }

    public function validate() {
        return [];
    }
}

$output = fopen("php://output", "w");
$ical = new ICalendar();
$ical->addProperty(new ICalProperty("X-TEST: Sample Value"));

$writer = new ICalWriter($output);
$writer->write($ical);
$writer->close();
--EXPECT--
