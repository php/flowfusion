--TEST--
Translated seed (inferredbugs-csharp: Coevery/1)
--FILE--
<?php

class Factory {
    public static function createInstance() {
        return new DefaultEventBus();
    }
}

interface IEventBus {}

class DefaultEventBus implements IEventBus {}

class App {
    private $eventBus;

    public function __construct(IEventBus $eventBus) {
        $this->eventBus = $eventBus;
    }
}

function main() {
    $eventBus = Factory::createInstance();
    $app = new App($eventBus);
}

main();
--EXPECT--
