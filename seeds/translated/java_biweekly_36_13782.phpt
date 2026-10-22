--TEST--
Translated seed (inferredbugs-java: biweekly/36)
--FILE--
<?php

class ICalendar {
    private $properties = [];
    
    public function setProductId($id) {
        $this->properties['PRODID'] = $id;
    }
    
    public function setVersion($version) {
        $this->properties['VERSION'] = $version;
    }

    public function addEvent($event) {
        $this->properties['VEVENT'][] = $event;
    }

    public function toString() {
        $output = "BEGIN:VCALENDAR\r\n";
        foreach ($this->properties as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $event) {
                    $output .= $event->toString();
                }
            } else {
                $output .= "$key:$value\r\n";
            }
        }
        $output .= "END:VCALENDAR\r\n";
        return $output;
    }
}

class VEvent {
    private $properties = [];

    public function setSummary($summary) {
        $this->properties['SUMMARY'] = $summary;
    }

    public function toString() {
        $output = "BEGIN:VEVENT\r\n";
        foreach ($this->properties as $key => $value) {
            $output .= "$key:$value\r\n";
        }
        $output .= "END:VEVENT\r\n";
        return $output;
    }
}

$ical = new ICalendar();
$ical->setProductId("-//XYZ Corp//NONSGML PDA Calendar Version 1.0//EN");
$ical->setVersion("2.0");

$event = new VEvent();
$event->setSummary("Networld+Interop Conference");
$ical->addEvent($event);

echo $ical->toString();
--EXPECT--
