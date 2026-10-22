--TEST--
Translated seed (inferredbugs-java: biweekly/39)
--FILE--
<?php

class ICalWriter {
    private $stream;
    private $warnings = [];

    public function __construct($stream) {
        $this->stream = $stream;
    }

    public function write($calendar) {
        fwrite($this->stream, "BEGIN:VCALENDAR\r\n");
        fwrite($this->stream, "VERSION:2.0\r\n");
        fwrite($this->stream, "PRODID:.*?\r\n");
        foreach ($calendar->events as $event) {
            fwrite($this->stream, "BEGIN:VEVENT\r\n");
            fwrite($this->stream, "UID:.*?\r\n");
            fwrite($this->stream, "DTSTAMP:.*?\r\n");
            fwrite($this->stream, "SUMMARY:" . $event['summary'] . "\r\n");
            fwrite($this->stream, "END:VEVENT\r\n");
        }
        fwrite($this->stream, "END:VCALENDAR\r\n");
    }

    public function close() {
        fclose($this->stream);
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

$ical = new ICalendar();
$event = ['summary' => "summary\nof event"];
$ical->addEvent($event);

$sw = fopen('php://temp', 'r+');
$writer = new ICalWriter($sw);
$writer->write($ical);
rewind($sw);
$actual = stream_get_contents($sw);
$writer->close();

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

if (preg_match("/$expected/", $actual)) {
    echo "Test passed\n";
} else {
    echo "Test failed\n";
}
--EXPECT--
