--TEST--
Translated seed (inferredbugs-csharp: Cirqus/2)
--FILE--
<?php

class TestContext {
    public $history = [];
    
    public function save($aggregateRootId, $event) {
        $this->history[] = $event;
    }

    public function getHistory() {
        return $this->history;
    }
}

class AggregateRoot {
    protected function emit($event) {
        // Logic to emit the event
    }
}

class DomainEvent {
    // DomainEvent base class logic
}

class AnAggregate extends AggregateRoot {
    public $processedEvents = 0;

    public function apply($event) {
        $this->processedEvents++;
    }

    public function doStuff() {
        $this->emit(new AnEvent());
    }
}

class AnEvent extends DomainEvent {
    // Event specific logic
}

function main() {
    $context = new TestContext();
    
    $rootId = uuid_create(UUID_TYPE_RANDOM);
    $context->save($rootId, new AnEvent());
    $context->save($rootId, new AnEvent());
    $context->save($rootId, new AnEvent());

    $firstInstance = new AnAggregate();
    $firstInstance->apply(new AnEvent());
    $firstInstance->apply(new AnEvent());
    $firstInstance->apply(new AnEvent());

    // Simulating checking processed events count
    echo "Processed Events: " . $firstInstance->processedEvents . PHP_EOL;

    // Simulating save operation and retrieving history
    $history = $context->getHistory();
    echo "History Count: " . count($history) . PHP_EOL;
}

main();
--EXPECT--
