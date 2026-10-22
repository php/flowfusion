--TEST--
Translated seed (inferredbugs-java: commons-pool/15)
--FILE--
<?php

class GenericKeyedObjectPool {
    const WHEN_EXHAUSTED_FAIL = 0;
    const WHEN_EXHAUSTED_BLOCK = 1;
    const WHEN_EXHAUSTED_GROW = 2;

    private $maxActive = 8;
    private $maxIdle = 8;
    private $maxTotal = -1;
    private $whenExhaustedAction = self::WHEN_EXHAUSTED_BLOCK;
    private $pools = [];

    public function borrowObject($key) {
        $pool = $this->getPool($key);
        if ($pool['active'] >= $this->maxActive) {
            if ($this->whenExhaustedAction == self::WHEN_EXHAUSTED_FAIL) {
                throw new Exception("No available objects");
            } elseif ($this->whenExhaustedAction == self::WHEN_EXHAUSTED_BLOCK) {
                while ($pool['active'] >= $this->maxActive) {
                    usleep(100); 
                }
            } 
        }
        
        $obj = array_pop($pool['objects']);
        if ($obj === null) {
            $obj = new stdClass();
        }
        
        $pool['active']++;
        $this->pools[$key] = $pool;
        return $obj;
    }

    public function returnObject($key, $obj) {
        $pool = $this->getPool($key);
        if ($pool['active'] > 0) {
            $pool['active']--;
            $pool['objects'][] = $obj;
        }
        $this->pools[$key] = $pool;
    }

    private function getPool($key) {
        if (!isset($this->pools[$key])) {
            $this->pools[$key] = ['active' => 0, 'objects' => []];
        }
        return $this->pools[$key];
    }
}

$pool = new GenericKeyedObjectPool();

$object1 = $pool->borrowObject("key1");
$pool->returnObject("key1", $object1);

$object2 = $pool->borrowObject("key1");
$pool->returnObject("key1", $object2);
--EXPECT--
