--TEST--
Translated seed (inferredbugs-java: commons-pool/9)
--FILE--
<?php
class GenericKeyedObjectPool {
    const WHEN_EXHAUSTED_FAIL = 0;
    const WHEN_EXHAUSTED_BLOCK = 1;
    const WHEN_EXHAUSTED_GROW = 2;
    private $maxActive = 8;
    private $maxIdle = 8;
    private $whenExhaustedAction = self::WHEN_EXHAUSTED_BLOCK;
    private $objectPools = [];
    private $activeCount = 0;

    public function borrowObject($key) {
        if (!isset($this->objectPools[$key])) {
            $this->objectPools[$key] = [];
        }

        if (count($this->objectPools[$key]) > 0) {
            return array_pop($this->objectPools[$key]);
        }

        if ($this->activeCount < $this->maxActive) {
            $this->activeCount++;
            return new stdClass(); // Simulate object creation
        }

        switch ($this->whenExhaustedAction) {
            case self::WHEN_EXHAUSTED_FAIL:
                throw new Exception("Pool exhausted");
            case self::WHEN_EXHAUSTED_BLOCK:
                // In a real scenario, we would wait here until an object is available
                while ($this->activeCount >= $this->maxActive) {
                    usleep(100); // Simulate waiting
                }
                return $this->borrowObject($key); // Recursively attempt to borrow
            case self::WHEN_EXHAUSTED_GROW:
                $this->activeCount++;
                return new stdClass(); // Simulate object creation
        }
    }

    public function returnObject($key, $object) {
        if (!isset($this->objectPools[$key])) {
            $this->objectPools[$key] = [];
        }

        if (count($this->objectPools[$key]) < $this->maxIdle) {
            $this->objectPools[$key][] = $object;
        } else {
            $this->activeCount--; // If pool is full, we discard the object
        }
    }
}

$pool = new GenericKeyedObjectPool();
$obj1 = $pool->borrowObject('key1');
$pool->returnObject('key1', $obj1);
$obj2 = $pool->borrowObject('key1');
--EXPECT--
