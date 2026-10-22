--TEST--
Translated seed (inferredbugs-csharp: Eff/3)
--FILE--
<?php

class EffTask {
    private $completed = false;
    private $result;

    public function complete($result) {
        $this->completed = true;
        $this->result = $result;
    }

    public function isCompleted() {
        return $this->completed;
    }

    public function getResult() {
        if (!$this->completed) {
            throw new Exception("Task not completed");
        }
        return $this->result;
    }

    public function asTask() {
        return $this;
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
        if ($this->eff->isCompleted()) {
            $continuation();
        } else {
            // Simulate async behavior
            register_shutdown_function($continuation);
        }
    }

    public function unsafeOnCompleted(callable $continuation) {
        $this->onCompleted($continuation);
    }
}

function main() {
    $task = new EffTask();
    $awaiter = new EffTaskAwaiter($task);

    $task->complete("Hello, World!");
    
    $awaiter->onCompleted(function() use ($awaiter) {
        echo $awaiter->getResult();
    });
}

main();
--EXPECT--
