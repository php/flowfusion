--TEST--
Translated seed (inferredbugs-java: asterisk-java/46)
--FILE--
<?php

class Event {}
class PoisonQueueEvent extends Event {}
class EventLifeMonitor {
    private $event;
    public function __construct($event) {
        $this->event = $event;
    }
    public function getEvent() {
        return $this->event;
    }
    public function assessAge() {}
}

class CoherentManagerEventQueue {
    private $eventQueue;
    private $stop = false;

    public function __construct() {
        $this->eventQueue = [];
        $this->start();
    }

    private function start() {
        $thread = function() {
            while (!$this->stop) {
                sleep(2);
                if (!empty($this->eventQueue)) {
                    $elm = array_shift($this->eventQueue);
                    if ($elm->getEvent() instanceof PoisonQueueEvent) break;
                    $this->dispatchEvent($elm->getEvent());
                }
            }
        };
        $thread();
    }

    public function onManagerEvent($event) {
        $this->eventQueue[] = new EventLifeMonitor($event);
    }

    public function stop() {
        $this->stop = true;
        $this->eventQueue[] = new EventLifeMonitor(new PoisonQueueEvent());
    }

    private function dispatchEvent($event) {
        // Simulate event dispatching
    }
}

$queue = new CoherentManagerEventQueue();
$queue->onManagerEvent(new Event());
$queue->stop();
--EXPECT--
