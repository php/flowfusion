--TEST--
Translated seed (inferredbugs-java: commons-pool/24)
--FILE--
<?php

class GenericKeyedObjectPool {
    private $maxTotalPerKey = 8;
    private $maxTotal = -1;
    private $blockWhenExhausted = true;
    private $maxWait = -1;
    private $pools = [];
    private $totalCreated = 0;

    public function borrowObject($key) {
        if (!isset($this->pools[$key])) {
            $this->pools[$key] = [];
        }
        if (count($this->pools[$key]) > 0) {
            return array_pop($this->pools[$key]);
        }

        if ($this->maxTotalPerKey > 0 && count($this->pools[$key]) >= $this->maxTotalPerKey) {
            if ($this->blockWhenExhausted) {
                throw new Exception("Pool exhausted for key: $key");
            }
            return null;
        }

        $this->totalCreated++;
        return new stdClass(); // Simulating object creation
    }

    public function returnObject($key, $object) {
        if (!isset($this->pools[$key])) {
            $this->pools[$key] = [];
        }
        if ($this->maxTotal > -1 && $this->totalCreated >= $this->maxTotal) {
            throw new Exception("Total limit reached");
        }
        $this->pools[$key][] = $object;
    }

    public function clearPool($key) {
        if (isset($this->pools[$key])) {
            unset($this->pools[$key]);
        }
    }
}

$pool = new GenericKeyedObjectPool();
try {
    $obj1 = $pool->borrowObject('key1');
    $obj2 = $pool->borrowObject('key1');
    $pool->returnObject('key1', $obj1);
    $pool->clearPool('key1');
} catch (Exception $e) {
    echo $e->getMessage();
}
--EXPECT--
