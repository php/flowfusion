--TEST--
Translated seed (inferredbugs-csharp: Cirqus/3)
--FILE--
<?php

class Command {
    protected $aggregateRootId;
    
    public function __construct($aggregateRootId) {
        $this->aggregateRootId = $aggregateRootId;
    }

    public function execute($aggregateRoot) {
        // to be implemented in subclasses
    }
}

class SomeRoot {
    public function doSomething(...$idsToDoSomethingTo) {
        $this->emit(new SomethingHappened());

        foreach ($idsToDoSomethingTo as $id) {
            $this->load($id)->doSomething();
        }

        foreach ($idsToDoSomethingTo as $id) {
            $this->load($id)->doSomethingElse();
        }
    }

    public function doSomethingElse() {
        $this->emit(new SomethingElseHappened());
    }

    public function load($id) {
        return new SomeRoot(); // Simulate loading another instance
    }

    public function emit($event) {
        // Simulate event emission
    }

    public function apply($event) {
        // Apply event logic
    }
}

class SomethingHappened {}
class SomethingElseHappened {}

class DoSomethingToABunchOfRootsCommand extends Command {
    private $additionalRootIds;

    public function __construct($aggregateRootId, ...$additionalRootIds) {
        parent::__construct($aggregateRootId);
        $this->additionalRootIds = $additionalRootIds;
    }

    public function execute($aggregateRoot) {
        $aggregateRoot->doSomething(...$this->additionalRootIds);
        $aggregateRoot->doSomethingElse();
    }
}

function main() {
    $root1Id = uniqid('', true);
    $root2Id = uniqid('', true);

    // Arrange
    echo "Processing initial two commands\n";
    $command1 = new DoSomethingToABunchOfRootsCommand($root1Id);
    $command2 = new DoSomethingToABunchOfRootsCommand($root2Id);

    $someRoot = new SomeRoot();
    $command1->execute($someRoot);
    $command2->execute($someRoot);

    // Act
    echo "\n\nActing...\n";
    $command3 = new DoSomethingToABunchOfRootsCommand($root1Id, $root2Id);
    $command3->execute($someRoot);
}

main();
--EXPECT--
