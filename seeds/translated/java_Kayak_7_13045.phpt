--TEST--
Translated seed (inferredbugs-java: Kayak/7)
--FILE--
<?php

class Bus {
    private $subscribedIDs = [];
    private $delta = 0;
    private $mode = 'STOP';
    
    public function play() {
        $this->mode = 'PLAY';
        $this->delta = 0;
    }
    
    public function stop() {
        $this->mode = 'STOP';
        $this->delta = 0;
    }
    
    public function newFrame($timestamp) {
        if ($this->mode === 'PLAY') {
            if ($timestamp !== 0) {
                if ($this->delta === 0) {
                    $this->delta = $timestamp - time();
                }
                $timestamp -= $this->delta;
            } else {
                $timestamp = time();
            }
            // Simulate frame delivery
            echo "Frame delivered with timestamp: $timestamp\n";
        }
    }

    public function addSubscription($id) {
        if (!in_array($id, $this->subscribedIDs)) {
            $this->subscribedIDs[] = $id;
        }
    }

    public function unsubscribe($id) {
        if (in_array($id, $this->subscribedIDs)) {
            $this->subscribedIDs = array_diff($this->subscribedIDs, [$id]);
        }
    }
}

$bus = new Bus();
$bus->play();
$bus->newFrame(0);
$bus->newFrame(100);
$bus->addSubscription(1);
$bus->unsubscribe(1);
$bus->stop();
$bus->newFrame(200);
--EXPECT--
