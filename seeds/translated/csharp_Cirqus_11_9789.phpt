--TEST--
Translated seed (inferredbugs-csharp: Cirqus/11)
--FILE--
<?php

class CommandProcessingException extends Exception {}

interface IEmittable {
    public function emit($event);
}

class AggregateRoot implements IEmittable {
    public function emit($event) {
        // Emit logic here
    }
}

class DomainEvent {}

class MyEvent extends DomainEvent {
    // MyEvent specific logic
}

class MyRoot extends AggregateRoot {
    public function emitMyEvent() {
        $this->emit(new MyEvent());
    }

    public function apply(MyEvent $e) {
        // Apply event logic here
    }
}

class Command {
    protected $aggregateRootId;

    public function __construct($aggregateRootId) {
        $this->aggregateRootId = $aggregateRootId;
    }

    public function execute($context) {
        // To be overridden
    }
}

class MyCommand extends Command {
    public function execute($context) {
        $aggregateRoot = $context->load($this->aggregateRootId, true);
        $aggregateRoot->emitMyEvent();
        $aggregateRoot->emitMyEvent();
    }
}

class ErronousCommand extends Command {
    public function execute($aggregateRoot) {
        throw new Exception("oh no, you cannot do that");
    }
}

class Context {
    public function load($id, $createIfNotExists = false) {
        return new MyRoot(); // Simplified for demonstration
    }
}

class CommandProcessor {
    public function processCommand($command) {
        $context = new Context();
        
        try {
            $command->execute($context);
        } catch (Exception $ex) {
            throw new CommandProcessingException($ex->getMessage(), 0, $ex);
        }
    }
}

function main() {
    $commandProcessor = new CommandProcessor();

    try {
        $commandProcessor->processCommand(new MyCommand("1"));
    } catch (CommandProcessingException $ex) {
        echo $ex->getMessage();
    }

    try {
        $commandProcessor->processCommand(new ErronousCommand("1"));
    } catch (CommandProcessingException $ex) {
        echo $ex->getMessage(); // Should output the error message
    }
}

main();
--EXPECT--
