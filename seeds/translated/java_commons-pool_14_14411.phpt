--TEST--
Translated seed (inferredbugs-java: commons-pool/14)
--FILE--
<?php

class GenericObjectPool {
    const WHEN_EXHAUSTED_FAIL = 0;
    const WHEN_EXHAUSTED_BLOCK = 1;
    const WHEN_EXHAUSTED_GROW = 2;
    const DEFAULT_MAX_ACTIVE = 8;
    const DEFAULT_WHEN_EXHAUSTED_ACTION = self::WHEN_EXHAUSTED_BLOCK;

    private $maxActive;
    private $whenExhaustedAction;
    private $pool;
    private $numActive = 0;
    private $allocationQueue;

    public function __construct($maxActive = self::DEFAULT_MAX_ACTIVE) {
        $this->maxActive = $maxActive;
        $this->whenExhaustedAction = self::DEFAULT_WHEN_EXHAUSTED_ACTION;
        $this->pool = [];
        $this->allocationQueue = [];
    }

    public function borrowObject() {
        if ($this->numActive < $this->maxActive) {
            $obj = $this->createObject();
            $this->numActive++;
            return $obj;
        }

        switch ($this->whenExhaustedAction) {
            case self::WHEN_EXHAUSTED_BLOCK:
                while ($this->numActive >= $this->maxActive) {
                    sleep(1); // Simulate blocking
                }
                return $this->borrowObject();
            case self::WHEN_EXHAUSTED_FAIL:
                throw new Exception("Pool exhausted");
            case self::WHEN_EXHAUSTED_GROW:
                return $this->createObject();
            default:
                throw new Exception("Invalid action");
        }
    }

    public function returnObject($obj) {
        $this->pool[] = $obj;
        $this->numActive--;
    }

    private function createObject() {
        return new stdClass(); // Simulated object creation
    }

    public function setWhenExhaustedAction($action) {
        $this->whenExhaustedAction = $action;
    }
}

$pool = new GenericObjectPool();
try {
    $obj1 = $pool->borrowObject();
    $obj2 = $pool->borrowObject();
    $pool->returnObject($obj1);
    $pool->returnObject($obj2);
    $obj3 = $pool->borrowObject();
} catch (Exception $e) {
    echo $e->getMessage();
}
--EXPECT--
