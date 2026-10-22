--TEST--
Translated seed (inferredbugs-java: biweekly/31)
--FILE--
<?php

class ICalWriter {
    private $output;
    private $warnings = [];

    public function __construct($output) {
        $this->output = $output;
    }

    public function write($ical) {
        $this->output .= "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:.*?\r\n";
        foreach ($ical->events as $event) {
            $this->output .= "BEGIN:VEVENT\r\n";
            $this->output .= "UID:.*?\r\n";
            $this->output .= "DTSTAMP:.*?\r\n";
            $this->output .= "SUMMARY:" . $event->summary . "\r\n";
            $this->output .= "END:VEVENT\r\n";
        }
        $this->output .= "END:VCALENDAR\r\n";
    }

    public function close() {
        // No operation in this context
    }

    public function getWarnings() {
        return $this->warnings;
    }
}

class ICalendar {
    public $events = [];

    public function addEvent($event) {
        $this->events[] = $event;
    }
}

class VEvent {
    public $summary;

    public function setSummary($summary) {
        $this->summary = $summary;
    }
}

$ical = new ICalendar();
$event = new VEvent();
$event->setSummary("summary\nof event");
$ical->addEvent($event);

$output = "";
$writer = new ICalWriter($output);
$writer->write($ical);
$writer->close();

$actual = $output;
$expected = 
"BEGIN:VCALENDAR\r\n" .
"VERSION:2.0\r\n" .
"PRODID:.*?\r\n" .
"BEGIN:VEVENT\r\n" .
"UID:.*?\r\n" .
"DTSTAMP:.*?\r\n" .
"SUMMARY:summary\\\\nof event\r\n" .
"END:VEVENT\r\n" .
"END:VCALENDAR\r\n";

echo preg_match("/$expected/", $actual) ? "Matched" : "Not Matched";
--EXPECT--
