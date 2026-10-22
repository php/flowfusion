--TEST--
Translated seed (inferredbugs-java: Kayak/19)
--FILE--
<?php

class Bus {
    private $subscriptionsRAW = [];
    private $subscriptionsBCM = [];
    private $subscribedIDs = [];
    private $delta = 0;

    public function addSubscription($subscription) {
        if ($subscription->getSubscribeAll()) {
            $this->subscriptionsRAW[] = $subscription;
        } else {
            $this->subscriptionsBCM[] = $subscription;
        }
    }

    public function subscribed($id, $subscription) {
        if (in_array($subscription, $this->subscriptionsBCM)) {
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
        // Simulate frame delivery logic
    }
}

$bus = new Bus();
$sub1 = new class {
    public function getSubscribeAll() {
        return false;
    }
};
$sub2 = new class {
    public function getSubscribeAll() {
        return true;
    }
};

$bus->addSubscription($sub1);
$bus->addSubscription($sub2);
$bus->subscribed(1, $sub1);
$bus->subscribed(2, $sub2);
$bus->newFrame(0);
$bus->newFrame(1000);
--EXPECT--
