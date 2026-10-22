--TEST--
Translated seed (inferredbugs-java: biweekly/32)
--FILE--
<?php

class ICalWriter {
    private $sw;
    private $warnings = [];

    public function __construct($sw) {
        $this->sw = $sw;
    }

    public function write($ical) {
        $this->sw .= "BEGIN:VCALENDAR\r\n";
        $this->sw .= "VERSION:2.0\r\n";
        $this->sw .= "PRODID:-//ABC Corporation//NONSGML My Product//EN\r\n";
        foreach ($ical->getEvents() as $event) {
            $this->sw .= "BEGIN:VEVENT\r\n";
            $this->sw .= "UID:{$event->getUid()}\r\n";
            $this->sw .= "SUMMARY:{$event->getSummary()}\r\n";
            $this->sw .= "END:VEVENT\r\n";
        }
        $this->sw .= "END:VCALENDAR\r\n";
    }

    public function close() {
        // no action needed
    }

    public function getWarnings() {
        return $this->warnings;
    }
}

class ICalendar {
    private $events = [];

    public function addEvent($event) {
        $this->events[] = $event;
    }

    public function getEvents() {
        return $this->events;
    }
}

class VEvent {
    private $uid;
    private $summary;

    public function __construct($uid, $summary) {
        $this->uid = $uid;
        $this->summary = $summary;
    }

    public function getUid() {
        return $this->uid;
    }

    public function getSummary() {
        return $this->summary;
    }
}

$ical = new ICalendar();
$event = new VEvent("uid1@example.com", "Networld+Interop Conference");
$ical->addEvent($event);

$sw = "";
$writer = new ICalWriter($sw);
$writer->write($ical);
$writer->close();

echo $sw;
--EXPECT--
