--TEST--
Translated seed (inferredbugs-java: Kayak/5)
--FILE--
<?php

class Bus {
    private $name;
    private $listeners = [];

    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function addBusChangeListener($listener) {
        $this->listeners[] = $listener;
    }

    public function notifyListeners() {
        foreach ($this->listeners as $listener) {
            $listener->nameChanged();
        }
    }
}

class BusStatistics {
    private $bus;

    public function setBus($bus) {
        $this->bus = $bus;
        $bus->addBusChangeListener($this);
        $this->updateName();
    }

    public function nameChanged() {
        $this->updateName();
    }

    private function updateName() {
        echo "Bus Statistics - " . $this->bus->getName() . "\n";
    }
}

$bus = new Bus("MyBus");
$statistics = new BusStatistics();
$statistics->setBus($bus);
$bus->notifyListeners();
--EXPECT--
