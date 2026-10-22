--TEST--
Translated seed (inferredbugs-java: commons-pool/23)
--FILE--
<?php

class KeyedObjectPool {
    private $maxTotalPerKey = 8;
    private $maxTotal = -1;
    private $poolMap = [];
    private $borrowedCount = 0;

    public function setMaxTotalPerKey($maxTotalPerKey) {
        $this->maxTotalPerKey = $maxTotalPerKey;
    }

    public function setMaxTotal($maxTotal) {
        $this->maxTotal = $maxTotal;
    }

    public function borrowObject($key) {
        if (!isset($this->poolMap[$key])) {
            $this->poolMap[$key] = [];
        }

        if (count($this->poolMap[$key]) >= $this->maxTotalPerKey) {
            throw new Exception("Pool exhausted for key $key");
        }

        $object = new stdClass(); // Simulating an object
        $this->poolMap[$key][] = $object;
        $this->borrowedCount++;

        return $object;
    }

    public function returnObject($key, $object) {
        if (!isset($this->poolMap[$key])) {
            throw new Exception("Object not from this pool");
        }

        $index = array_search($object, $this->poolMap[$key], true);
        if ($index === false) {
            throw new Exception("Object not currently borrowed");
        }

        array_splice($this->poolMap[$key], $index, 1);
    }

    public function getNumActive($key) {
        return isset($this->poolMap[$key]) ? count($this->poolMap[$key]) : 0;
    }
}

$pool = new KeyedObjectPool();
$pool->setMaxTotalPerKey(2);
$pool->setMaxTotal(4);

try {
    $obj1 = $pool->borrowObject('key1');
    $obj2 = $pool->borrowObject('key1');
    $obj3 = $pool->borrowObject('key1'); // Should throw an exception
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}

$pool->returnObject('key1', $obj1);
$pool->returnObject('key1', $obj2);
--EXPECT--
