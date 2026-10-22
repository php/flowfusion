--TEST--
Translated seed (inferredbugs-java: Kayak/17)
--FILE--
<?php

class Bus {
    private $subscriptionsRAW = [];
    private $subscriptionsBCM = [];
    private $delta = 0;
    private $timeSource;

    public function addSubscription($s) {
        if ($s->getSubscribeAll()) {
            $this->subscriptionsRAW[] = $s;
        } else {
            $this->subscriptionsBCM[] = $s;
        }
    }

    public function newFrame($f) {
        $timestamp = $f->getTimestamp();
        if ($timestamp != 0) {
            if ($this->delta == 0) {
                $this->delta = $timestamp - $this->timeSource->getTime();
            }
            $timestamp -= $this->delta;
            $f->setTimestamp($timestamp);
        } else {
            $f->setTimestamp($this->timeSource->getTime());
        }
    }

    public function setTimeSource($timeSource) {
        $this->timeSource = $timeSource;
    }
}

class Frame {
    private $timestamp;

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }
}

class TimeSource {
    public function getTime() {
        return time();
    }
}

$bus = new Bus();
$timeSource = new TimeSource();
$bus->setTimeSource($timeSource);
$frame = new Frame();
$frame->setTimestamp(0);
$bus->newFrame($frame);
--EXPECT--
