--TEST--
Translated seed (inferredbugs-java: Kayak/32)
--FILE--
<?php

class Bus {
    private $description;
    private $listeners = [];

    public function __construct($description) {
        $this->description = $description;
    }

    public function addBusChangeListener($listener) {
        $this->listeners[] = $listener;
    }

    public function removeBusChangeListener($listener) {
        $this->listeners = array_filter($this->listeners, function($l) use ($listener) {
            return $l !== $listener;
        });
    }

    public function getDescription() {
        return $this->description;
    }

    public function getName() {
        return "Bus " . $this->description;
    }

    public function notifyDescriptionChanged() {
        foreach ($this->listeners as $listener) {
            $listener->descriptionChanged();
        }
    }
}

class Project {
    private $buses = [];
    private $listeners = [];

    public function addBus($bus) {
        $this->buses[] = $bus;
        foreach ($this->listeners as $listener) {
            $listener->projectBusAdded($this, $bus);
        }
    }

    public function getBuses() {
        return $this->buses;
    }

    public function addProjectChangeListener($listener) {
        $this->listeners[] = $listener;
    }

    public function removeProjectChangeListener($listener) {
        $this->listeners = array_filter($this->listeners, function($l) use ($listener) {
            return $l !== $listener;
        });
    }
}

class BusFactory {
    private $project;

    public function __construct() {
        $this->project = new Project();
        $this->initializeBusChangeListener();
    }

    private function initializeBusChangeListener() {
        $listener = new class {
            public function descriptionChanged() {
                // Simulate refresh
            }
        };

        $bus = new Bus("Description 1");
        $bus->addBusChangeListener($listener);
        $this->project->addBus($bus);
        $bus->notifyDescriptionChanged();
    }
}

$factory = new BusFactory();
--EXPECT--
