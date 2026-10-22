--TEST--
Translated seed (inferredbugs-csharp: Eff/2)
--FILE--
<?php

class EffTask {
    private $isCompleted = false;
    private $result;

    public function complete($result) {
        $this->isCompleted = true;
        $this->result = $result;
    }

    public function isCompleted() {
        return $this->isCompleted;
    }

    public function getResult() {
        if (!$this->isCompleted) {
            throw new Exception("Task not completed");
        }
        return $this->result;
    }
}

class EffTaskAwaiter {
    private $eff;

    public function __construct(EffTask $eff) {
        $this->eff = $eff;
    }

    public function isCompleted() {
        return $this->eff->isCompleted();
    }

    public function getResult() {
        return $this->eff->getResult();
    }

    public function onCompleted(callable $continuation) {
        if ($this->isCompleted()) {
            $continuation();
        } else {
            // Simulate waiting for completion
            // In a real scenario, this would likely be an asynchronous wait
            sleep(1); // Simulate delay
            $continuation();
        }
    }

    public function unsafeOnCompleted(callable $continuation) {
        // Unsafe execution ignoring completion state
        $continuation();
    }
}

function main() {
    $task = new EffTask();
    $awaiter = new EffTaskAwaiter($task);

    // Start a simulated task completion
    // This would be done in a different execution context in real async code
    $task->complete("Hello, World!");

    $awaiter->onCompleted(function() use ($awaiter) {
        echo $awaiter->getResult();
    });
}

main();
--EXPECT--
