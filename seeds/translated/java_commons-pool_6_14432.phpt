--TEST--
Translated seed (inferredbugs-java: commons-pool/6)
--FILE--
<?php

class GenericKeyedObjectPool {
    private array $poolMap = [];
    private int $maxActive = 8;
    private int $totalActive = 0;

    public function borrowObject($key) {
        if (!isset($this->poolMap[$key])) {
            $this->poolMap[$key] = [];
        }
        
        if (count($this->poolMap[$key]) < $this->maxActive) {
            $this->totalActive++;
            return new stdClass(); // Simulating object creation
        } else {
            throw new Exception("Pool exhausted");
        }
    }

    public function returnObject($key, $obj) {
        if (isset($this->poolMap[$key])) {
            array_push($this->poolMap[$key], $obj);
            $this->totalActive--;
        }
    }

    public function getNumActive() {
        return $this->totalActive;
    }
}

function main() {
    $pool = new GenericKeyedObjectPool();

    try {
        for ($i = 0; $i < 10; $i++) {
            $pool->borrowObject('key1');
        }
    } catch (Exception $e) {
        echo $e->getMessage(); // Pool exhausted
    }
}

main();
--EXPECT--
