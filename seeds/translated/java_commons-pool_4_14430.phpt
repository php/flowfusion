--TEST--
Translated seed (inferredbugs-java: commons-pool/4)
--FILE--
<?php

class GenericKeyedObjectPool {
    private $maxActive = 8;
    private $maxIdle = 8;
    private $whenExhaustedAction = 1;
    private $poolMap = [];
    private $allocationQueue = [];

    public function borrowObject($key) {
        if (isset($this->poolMap[$key]) && count($this->poolMap[$key]) > 0) {
            return array_pop($this->poolMap[$key]);
        }

        if ($this->whenExhaustedAction === 1) { // WHEN_EXHAUSTED_BLOCK
            while (count($this->poolMap[$key]) >= $this->maxActive) {
                usleep(100000); // wait for 100ms
            }
        }

        if (!isset($this->poolMap[$key])) {
            $this->poolMap[$key] = [];
        }

        if (count($this->poolMap[$key]) < $this->maxActive) {
            $obj = new stdClass(); // Simulate object creation
            $this->poolMap[$key][] = $obj;
            return $obj;
        }

        throw new Exception("No objects available");
    }

    public function returnObject($key, $obj) {
        if (!isset($this->poolMap[$key])) {
            $this->poolMap[$key] = [];
        }

        if (count($this->poolMap[$key]) < $this->maxIdle) {
            $this->poolMap[$key][] = $obj;
        }
    }
}

$pool = new GenericKeyedObjectPool();
$key = 'myKey';

$object1 = $pool->borrowObject($key);
$object2 = $pool->borrowObject($key);
$pool->returnObject($key, $object1);
$pool->returnObject($key, $object2);
$object3 = $pool->borrowObject($key); // May borrow again without exceeding maxActive
--EXPECT--
