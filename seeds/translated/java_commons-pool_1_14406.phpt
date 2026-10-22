--TEST--
Translated seed (inferredbugs-java: commons-pool/1)
--FILE--
<?php

class KeyedObjectPool {
    private $pools = [];
    private $maxActive = 8;
    private $whenExhaustedAction = 1; // 0 - fail, 1 - block, 2 - grow
    private $maxTotal = -1; // no limit
    private $totalActive = 0;

    public function borrowObject($key) {
        if (!isset($this->pools[$key])) {
            $this->pools[$key] = [];
        }
        $pool = &$this->pools[$key];

        if (count($pool) > 0) {
            return array_pop($pool);
        }

        if ($this->maxTotal > 0 && $this->totalActive >= $this->maxTotal) {
            if ($this->whenExhaustedAction == 0) {
                throw new Exception("No object available");
            }
            if ($this->whenExhaustedAction == 1) {
                // Block until an object is available
                // For simplicity, we will return a new object here
                // Normally, you would implement a waiting mechanism
            }
        }

        $this->totalActive++;
        return new stdClass(); // just simulating object creation
    }

    public function returnObject($key, $obj) {
        if (!isset($this->pools[$key])) {
            $this->pools[$key] = [];
        }
        $this->pools[$key][] = $obj;
        $this->totalActive--;
    }

    public function setMaxActive($maxActive) {
        $this->maxActive = $maxActive;
    }

    public function setWhenExhaustedAction($action) {
        $this->whenExhaustedAction = $action;
    }

    public function setMaxTotal($maxTotal) {
        $this->maxTotal = $maxTotal;
    }
}

$pool = new KeyedObjectPool();
$pool->setMaxActive(2);
$pool->setMaxTotal(5);

try {
    $obj1 = $pool->borrowObject('key1');
    $obj2 = $pool->borrowObject('key1');
    $obj3 = $pool->borrowObject('key1'); // This should trigger when exhausted action
} catch (Exception $e) {
    echo $e->getMessage();
}

$pool->returnObject('key1', $obj1);
$pool->returnObject('key1', $obj2);
$pool->returnObject('key1', $obj3);
--EXPECT--
