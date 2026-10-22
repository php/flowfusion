--TEST--
Translated seed (inferredbugs-java: Kayak/15)
--FILE--
<?php

class Bus {
    private $subscriptionsRAW = [];
    private $subscriptionsBCM = [];
    private $subscribedIDs = [];
    private $delta = 0;

    public function addSubscription($s) {
        if ($s['subscribeAll']) {
            $this->subscriptionsRAW[] = $s;
        } else {
            $this->subscriptionsBCM[] = $s;
        }
    }

    public function subscribed($id, $s) {
        if (in_array($s, $this->subscriptionsBCM)) {
            if (!in_array($id, $this->subscribedIDs)) {
                $this->subscribedIDs[] = $id;
            }
        }
    }

    public function newFrame($timestamp) {
        if ($timestamp != 0) {
            if ($this->delta == 0) {
                $this->delta = $timestamp - time();
            }
            $timestamp -= $this->delta;
        } else {
            $timestamp = time();
        }
        $this->deliverFrame($timestamp);
    }

    private function deliverFrame($timestamp) {
        foreach ($this->subscriptionsRAW as $s) {
            // Simulate frame delivery with the modified timestamp
        }
        foreach ($this->subscriptionsBCM as $s) {
            // Simulate frame delivery with the modified timestamp
        }
    }
}

$bus = new Bus();
$bus->addSubscription(['subscribeAll' => true]);
$bus->newFrame(0);
$bus->newFrame(time() + 1000);
$bus->newFrame(time() + 2000);
--EXPECT--
