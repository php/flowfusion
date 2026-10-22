--TEST--
Translated seed (inferredbugs-java: biweekly/38)
--FILE--
<?php

class ICalWriter {
    private $output;
    private $warnings = 0;

    public function __construct(&$output) {
        $this->output = &$output;
    }

    public function write($ical) {
        $this->output .= "BEGIN:VCALENDAR\r\n";
        $this->output .= "VERSION:2.0\r\n";
        $this->output .= "PRODID:Example\r\n";
        foreach ($ical->events as $event) {
            $this->output .= "BEGIN:VEVENT\r\n";
            $this->output .= "UID:{$event->uid}\r\n";
            $this->output .= "SUMMARY:{$event->summary}\r\n";
            $this->output .= "END:VEVENT\r\n";
        }
        $this->output .= "END:VCALENDAR\r\n";
    }

    public function getWarnings() {
        return $this->warnings;
    }

    public function close() {
    }
}

class ICalendar {
    public $events = [];

    public function addEvent($event) {
        $this->events[] = $event;
    }
}

class VEvent {
    public $uid;
    public $summary;

    public function setUid($uid) {
        $this->uid = $uid;
    }

    public function setSummary($summary) {
        $this->summary = $summary;
    }
}

$output = '';
$ical = new ICalendar();
$event = new VEvent();
$event->setUid("uid1@example.com");
$event->setSummary("Networld+Interop Conference");
$ical->addEvent($event);

$writer = new ICalWriter($output);
$writer->write($ical);
$writer->close();

echo $output;
--EXPECT--
