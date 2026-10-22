--TEST--
Translated seed (inferredbugs-java: commons-pool/22)
--FILE--
<?php

class GenericKeyedObjectPool {
    private $maxTotalPerKey = 8;
    private $maxTotal = -1;
    private $idleObjects = [];
    private $activeCount = 0;

    public function borrowObject($key) {
        if ($this->activeCount >= $this->maxTotal && $this->maxTotal > 0) {
            throw new Exception("Pool exhausted");
        }

        if (!isset($this->idleObjects[$key])) {
            $this->idleObjects[$key] = [];
        }

        if (count($this->idleObjects[$key]) > 0) {
            $object = array_pop($this->idleObjects[$key]);
        } else {
            if ($this->activeCount >= $this->maxTotalPerKey) {
                throw new Exception("Max total per key reached");
            }
            $object = new stdClass(); // Simulate creating a new object
            $this->activeCount++;
        }

        return $object;
    }

    public function returnObject($key, $object) {
        if (!isset($this->idleObjects[$key])) {
            $this->idleObjects[$key] = [];
        }
        if (count($this->idleObjects[$key]) >= $this->maxTotalPerKey) {
            throw new Exception("Max idle reached for this key");
        }
        $this->idleObjects[$key][] = $object;
        $this->activeCount--;
    }
}

$pool = new GenericKeyedObjectPool();

try {
    $obj1 = $pool->borrowObject('key1');
    $obj2 = $pool->borrowObject('key1');
    $pool->returnObject('key1', $obj1);
    $obj3 = $pool->borrowObject('key1');
    $pool->returnObject('key1', $obj2);
    $pool->returnObject('key1', $obj3);
} catch (Exception $e) {
    echo $e->getMessage();
}
--EXPECT--
