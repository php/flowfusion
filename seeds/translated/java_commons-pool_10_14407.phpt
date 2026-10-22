--TEST--
Translated seed (inferredbugs-java: commons-pool/10)
--FILE--
<?php

class GenericKeyedObjectPool {
    private $maxActive = 8;
    private $queue = [];
    private $activeCount = 0;

    public function borrowObject($key) {
        if ($this->activeCount >= $this->maxActive) {
            throw new Exception("Pool exhausted");
        }

        if (isset($this->queue[$key]) && count($this->queue[$key]) > 0) {
            $object = array_pop($this->queue[$key]);
            $this->activeCount++;
            return $object;
        } else {
            $object = new stdClass(); // Create a new object
            $this->activeCount++;
            return $object;
        }
    }

    public function returnObject($key, $object) {
        if ($this->activeCount <= 0) {
            throw new Exception("No active instances to return");
        }

        if (!isset($this->queue[$key])) {
            $this->queue[$key] = [];
        }

        $this->queue[$key][] = $object;
        $this->activeCount--;
    }

    public function getNumActive() {
        return $this->activeCount;
    }
}

$pool = new GenericKeyedObjectPool();

try {
    $obj1 = $pool->borrowObject('key1');
    $obj2 = $pool->borrowObject('key1');
    $pool->returnObject('key1', $obj1);
    $pool->returnObject('key1', $obj2);
    echo "Active Count: " . $pool->getNumActive() . "\n"; // Should be 0
    $obj3 = $pool->borrowObject('key1'); // Should succeed
    echo "Active Count: " . $pool->getNumActive() . "\n"; // Should be 1
} catch (Exception $e) {
    echo $e->getMessage();
}
--EXPECT--
