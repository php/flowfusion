--TEST--
Translated seed (inferredbugs-java: biweekly/35)
--FILE--
<?php

class ICalendar {
    private $properties = [];
    private $components = [];

    public function addProperty($property) {
        $this->properties[] = $property;
    }

    public function addComponent($component) {
        $this->components[] = $component;
    }

    public function write() {
        $output = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\n";
        foreach ($this->properties as $property) {
            $output .= $property . "\r\n";
        }
        foreach ($this->components as $component) {
            $output .= $component->write();
        }
        $output .= "END:VCALENDAR\r\n";
        return $output;
    }
}

class VEvent {
    private $summary;

    public function setSummary($summary) {
        $this->summary = $summary;
    }

    public function write() {
        return "BEGIN:VEVENT\r\nSUMMARY:{$this->summary}\r\nEND:VEVENT\r\n";
    }
}

class TestProperty {
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function __toString() {
        return "X-TEST:{$this->value}";
    }
}

$ical = new ICalendar();
$ical->addProperty(new TestProperty("value"));

$event = new VEvent();
$event->setSummary("summary\nof event");
$ical->addComponent($event);

echo $ical->write();
--EXPECT--
