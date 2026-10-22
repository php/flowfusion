--TEST--
Translated seed (inferredbugs-java: Kayak/8)
--FILE--
<?php

class Bus {
    private $subscriptionsRAW = [];
    private $subscriptionsBCM = [];
    private $subscribedIDs = [];
    private $delta = 0;

    public function addSubscription($s) {
        if ($s->getSubscribeAll()) {
            $this->subscriptionsRAW[] = $s;
        } else {
            $this->subscriptionsBCM[] = $s;
        }
    }

    public function newFrame($timestamp) {
        if ($timestamp != 0) {
            if ($this->delta == 0) {
                $this->delta = $timestamp - (microtime(true) * 1000); // Simulate timeSource.getTime()
            }
            $timestamp -= $this->delta;
        }
        echo $timestamp . PHP_EOL; // Simulate frame processing
    }

    public function subscribeTo($id) {
        if (!in_array($id, $this->subscribedIDs)) {
            $this->subscribedIDs[] = $id;
        }
    }

    public function process() {
        foreach ($this->subscriptionsBCM as $s) {
            foreach ($s->getAllIdentifiers() as $id) {
                $this->subscribeTo($id);
            }
            $this->newFrame(1000); // Simulate new frame with a timestamp
        }
    }
}

class Subscription {
    private $subscribeAll;
    private $identifiers;

    public function __construct($subscribeAll, $identifiers) {
        $this->subscribeAll = $subscribeAll;
        $this->identifiers = $identifiers;
    }

    public function getSubscribeAll() {
        return $this->subscribeAll;
    }

    public function getAllIdentifiers() {
        return $this->identifiers;
    }
}

$bus = new Bus();
$bus->addSubscription(new Subscription(true, []));
$bus->addSubscription(new Subscription(false, [1, 2, 3]));
$bus->process();
--EXPECT--
