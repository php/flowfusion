--TEST--
Translated seed (inferredbugs-java: Kayak/6)
--FILE--
<?php

class Bus {
    private $name;
    private $listeners = [];

    public function __construct($name) {
        $this->name = $name;
    }

    public function addBusChangeListener($listener) {
        $this->listeners[] = $listener;
    }

    public function getName() {
        return $this->name;
    }

    public function notifyNameChanged() {
        foreach ($this->listeners as $listener) {
            $listener->nameChanged();
        }
    }
}

class BusStatisticsTopComponent {
    private static $instance;
    private $bus;

    public static function getDefault() {
        if (self::$instance === null) {
            self::$instance = new BusStatisticsTopComponent();
        }
        return self::$instance;
    }

    public function setBus(Bus $bus) {
        $this->bus = $bus;
        $this->bus->addBusChangeListener($this);
        $this->setName("BusStatisticsTopComponent - " . $bus->getName());
        $bus->notifyNameChanged();
    }

    public function nameChanged() {
        // No operation
    }

    private function setName($name) {
        // No operation
    }
}

$bus = new Bus("Main Bus");
$component = BusStatisticsTopComponent::getDefault();
$component->setBus($bus);
$bus->notifyNameChanged();
--EXPECT--
