--TEST--
Translated seed (inferredbugs-csharp: Cirqus/12)
--FILE--
<?php

class SomeRoot {
    public function possiblyDoSomething() {
        // no way!
    }
}

class ICommandContext {
    private $store = [];

    public function load($id, $createIfNotExists = false) {
        if (isset($this->store[$id])) {
            return $this->store[$id];
        } 
        if ($createIfNotExists) {
            $this->store[$id] = new SomeRoot();
            return $this->store[$id];
        }
        return null;
    }
}

class ExecutableCommand {
    public $someId;

    public function execute(ICommandContext $context) {
        $root = $context->load($this->someId, true);
        $root->possiblyDoSomething();
    }
}

function run() {
    $commandProcessor = new ICommandContext();
    $command = new ExecutableCommand();
    $command->someId = 'somekey';
    $command->execute($commandProcessor);
}

run();
--EXPECT--
