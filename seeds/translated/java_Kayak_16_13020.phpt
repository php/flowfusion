--TEST--
Translated seed (inferredbugs-java: Kayak/16)
--FILE--
<?php

class Bus {
    private $subscriptionsRAW = [];
    private $subscriptionsBCM = [];
    private $subscribedIDs = [];
    private $delta = 0;
    private $mode = 'STOP';

    public function addSubscription($s) {
        if ($s['subscribeAll']) {
            $this->subscriptionsRAW[] = $s;
            if ($this->mode == 'PLAY') $this->openRAWConnection();
        } else {
            $this->subscriptionsBCM[] = $s;
            if ($this->mode == 'PLAY') $this->openBCMConnection();
        }
    }

    public function newFrame($f) {
        if ($this->mode == 'PLAY') {
            $timestamp = $f['timestamp'];
            if ($timestamp != 0) {
                if ($this->delta == 0) {
                    $this->delta = $timestamp - time();
                }
                $timestamp -= $this->delta;
                $f['timestamp'] = $timestamp;
            } else {
                $f['timestamp'] = time();
            }
        }
    }

    public function safeUnsubscribe($identifier) {
        if (!in_array($identifier, $this->subscribedIDs)) {
            $this->subscribedIDs = array_diff($this->subscribedIDs, [$identifier]);
        }
    }

    public function openBCMConnection() {
        // Simulate opening a BCM connection
    }

    public function openRAWConnection() {
        // Simulate opening a RAW connection
    }

    public function play() {
        $this->mode = 'PLAY';
        if (!empty($this->subscriptionsBCM)) $this->openBCMConnection();
        if (!empty($this->subscriptionsRAW)) $this->openRAWConnection();
    }

    public function stop() {
        $this->mode = 'STOP';
        $this->delta = 0;
    }
}

$bus = new Bus();
$bus->addSubscription(['subscribeAll' => true]);
$bus->play();

$frame = ['timestamp' => 0];
$bus->newFrame($frame);
$bus->stop();
--EXPECT--
