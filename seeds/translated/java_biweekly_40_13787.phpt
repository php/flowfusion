--TEST--
Translated seed (inferredbugs-java: biweekly/40)
--FILE--
<?php

class ICalendar {
    private $properties = [];
    private $components = [];
    
    public function setProductId($id) {
        $this->addProperty("PRODID:$id");
    }

    public function setVersion($version) {
        $this->addProperty("VERSION:$version");
    }

    public function addProperty($property) {
        $this->properties[] = $property;
    }

    public function addComponent($component) {
        $this->components[] = $component;
    }

    public function write() {
        $output = "BEGIN:VCALENDAR\r\n" . implode("\r\n", $this->properties) . "\r\n";
        foreach ($this->components as $component) {
            $output .= $component->write();
        }
        $output .= "END:VCALENDAR\r\n";
        return $output;
    }
}

class VEvent {
    private $properties = [];

    public function setDateTimeStamp($dateTime) {
        $this->properties[] = "DTSTAMP:$dateTime";
    }

    public function setUid($uid) {
        $this->properties[] = "UID:$uid";
    }

    public function setSummary($summary) {
        $this->properties[] = "SUMMARY:$summary";
    }

    public function write() {
        return "BEGIN:VEVENT\r\n" . implode("\r\n", $this->properties) . "\r\nEND:VEVENT\r\n";
    }
}

$ical = new ICalendar();
$ical->setProductId("-//Example Corp//NONSGML My Calendar//EN");
$ical->setVersion("2.0");

$event = new VEvent();
$event->setDateTimeStamp("20230101T120000Z");
$event->setUid("uid@example.com");
$event->setSummary("Sample Event");
$ical->addComponent($event);

echo $ical->write();
--EXPECT--
