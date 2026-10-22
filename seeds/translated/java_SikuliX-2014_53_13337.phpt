--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/53)
--FILE--
<?php

class Observer {
    private $eventStates = [];
    private $eventCounts = [];
    private $eventNames = [];
    private $eventTypes = [];
    private $shouldStopOnFirstEvent = false;

    private const FIRST = 0;
    private const INACTIVE = 5;
    private const HAPPENED = 4;

    public function initialize() {
        foreach ($this->eventNames as $name => $ptn) {
            $this->eventStates[$name] = self::FIRST;
            $this->eventCounts[$name] = 0;
        }
        $this->shouldStopOnFirstEvent = false;
    }

    public function addObserver($ptn, $name, $type) {
        $this->eventNames[$name] = $ptn;
        $this->eventTypes[$name] = $type;
        $this->eventStates[$name] = self::FIRST;
    }

    public function checkPatterns() {
        foreach ($this->eventStates as $name => $state) {
            if ($state === self::FIRST) {
                // Simulate a pattern check that might succeed
                $hasMatch = rand(0, 1) === 1;
                if ($hasMatch) {
                    $this->eventStates[$name] = self::HAPPENED;
                    $this->eventCounts[$name]++;
                    if ($this->shouldStopOnFirstEvent) {
                        break;
                    }
                }
            }
        }
    }

    public function run() {
        $this->initialize();
        $this->addObserver("Pattern1", "Observer1", "CHANGE");
        $this->checkPatterns();
        foreach ($this->eventCounts as $name => $count) {
            echo "Observer $name has count: $count\n";
        }
    }
}

$observer = new Observer();
$observer->run();
--EXPECT--
