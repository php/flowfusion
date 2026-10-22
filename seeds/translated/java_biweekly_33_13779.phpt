--TEST--
Translated seed (inferredbugs-java: biweekly/33)
--FILE--
<?php

class ICalendar {
    private $events = [];
    private $properties = [];
    
    public function addEvent($event) {
        $this->events[] = $event;
    }
    
    public function addProperty($property) {
        $this->properties[] = $property;
    }

    public function write() {
        $output = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\n";
        foreach ($this->properties as $property) {
            $output .= "$property\r\n";
        }
        foreach ($this->events as $event) {
            $output .= $event->write();
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
        return "PRODID:{$this->value}";
    }
}

$ical = new ICalendar();
$ical->addProperty(new TestProperty("-//XYZ Corp//NONSGML PDA Calendar Version 1.0//EN"));
$event = new VEvent();
$event->setSummary("summary\nof event");
$ical->addEvent($event);
$actual = $ical->write();
echo $actual;
--EXPECT--
