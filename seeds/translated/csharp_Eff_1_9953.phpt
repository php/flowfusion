--TEST--
Translated seed (inferredbugs-csharp: Eff/1)
--FILE--
<?php

class EffTask {
    private $isCompleted;
    private $result;

    public function __construct() {
        $this->isCompleted = false;
        $this->result = null;
    }

    public function complete($result) {
        $this->isCompleted = true;
        $this->result = $result;
    }

    public function isCompleted() {
        return $this->isCompleted;
    }

    public function getResult() {
        if (!$this->isCompleted) {
            throw new Exception("Task not completed yet.");
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
        if ($this->isCompleted()) {
            $continuation();
        } else {
            // Simulating the asynchronous continuation
            $this->fakeAwait($continuation);
        }
    }

    public function unsafeOnCompleted(callable $continuation) {
        $this->onCompleted($continuation);
    }

    private function fakeAwait(callable $continuation) {
        // Simulate delayed completion for demonstration
        sleep(1); // Simulating async work
        $this->eff->complete("Task Result");
        $continuation();
    }
}

function main() {
    $effTask = new EffTask();
    $await = new EffTaskAwaiter($effTask);

    $await->onCompleted(function() use ($await) {
        echo $await->getResult();
    });
}

main();
--EXPECT--
