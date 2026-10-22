--TEST--
Translated seed (inferredbugs-java: biweekly/42)
--FILE--
<?php

date_default_timezone_set('America/New_York');

class ICalendar {
    private $events = [];

    public function addEvent($event) {
        $this->events[] = $event;
    }

    public function __toString() {
        $output = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//xyz Corp//NONSGML PDA Calendar Version 1.0//EN\r\n";
        foreach ($this->events as $event) {
            $output .= $event;
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

    public function __toString() {
        return "BEGIN:VEVENT\r\nSUMMARY:{$this->summary}\r\nEND:VEVENT\r\n";
    }
}

$ical = new ICalendar();

$event = new VEvent();
$event->setSummary("summary\nof event");
$ical->addEvent($event);

$actual = (string)$ical;

$expected = 
"BEGIN:VCALENDAR\r\n" .
"VERSION:2\\.0\r\n" .
"PRODID:.*?\r\n" .
"BEGIN:VEVENT\r\n" .
"SUMMARY:summary\\\\nof event\r\n" .
"END:VEVENT\r\n" .
"END:VCALENDAR\r\n";

echo preg_match("/$expected/", $actual) ? "Matched\n" : "Not Matched\n";
--EXPECT--
