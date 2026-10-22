--TEST--
Translated seed (inferredbugs-java: asterisk-java/49)
--FILE--
<?php

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

class PoisonQueueEvent {}

class CoherentManagerEventQueue implements Runnable {
    private $eventQueue = [];
    private $stop = false;
    
    public function onManagerEvent($event) {
        if ($event instanceof PoisonQueueEvent) {
            $this->stop = true;
            return;
        }
        $this->eventQueue[] = new EventLifeMonitor($event);
    }
    
    public function run() {
        while (!$this->stop) {
            if ($this->eventQueue) {
                $elm = array_shift($this->eventQueue);
                if ($elm) {
                    $event = $elm->getEvent();
                    $this->dispatchEvent($event);
                    $elm->assessAge();
                }
            }
            usleep(2000000); // Sleep for 2 seconds
        }
    }
    
    public function dispatchEvent($event) {
        // Simulating event processing
        usleep(100000); // Simulating some processing time
    }

    public function stop() {
        $this->onManagerEvent(new PoisonQueueEvent());
    }
}

$queue = new CoherentManagerEventQueue();
$thread = new Thread(function() use ($queue) {
    $queue->run();
});

$thread->start();

$queue->onManagerEvent("ExampleEvent1");
$queue->onManagerEvent("ExampleEvent2");
$queue->onManagerEvent("ExampleEvent3");

usleep(5000000); // Let it process for a while
$queue->stop();
$thread->join();
--EXPECT--
