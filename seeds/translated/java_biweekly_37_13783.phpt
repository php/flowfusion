--TEST--
Translated seed (inferredbugs-java: biweekly/37)
--FILE--
<?php

class ICalendar {
    private $properties = [];

    public function clear() {
        $this->properties = [];
    }

    public function setProductId($id) {
        $this->properties['PRODID'] = $id;
    }

    public function setVersion($version) {
        $this->properties['VERSION'] = $version;
    }

    public function addExperimentalProperty($name, $value) {
        $this->properties[$name] = $value;
    }

    public function write() {
        $output = "BEGIN:VCALENDAR\r\n";
        foreach ($this->properties as $key => $value) {
            $output .= "$key:$value\r\n";
        }
        $output .= "END:VCALENDAR\r\n";
        return $output;
    }
}

$ical = new ICalendar();
$ical->clear();
$ical->setProductId("-//ABC Corporation//NONSGML My Product//EN");
$ical->setVersion("2.0");
$ical->addExperimentalProperty("X-NUMBER", "1");
$ical->addExperimentalProperty("X-NUMBER", "2");

echo $ical->write();
--EXPECT--
