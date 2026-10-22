--TEST--
Translated seed (inferredbugs-java: commons-pool/28)
--FILE--
<?php

class GenericKeyedObjectPool {
    private $maxTotalPerKey = 8;
    private $maxTotal = -1;
    private $blockWhenExhausted = true;
    private $maxWait = -1;
    private $idlePools = [];
    
    public function borrowObject($key) {
        if (!isset($this->idlePools[$key])) {
            $this->idlePools[$key] = [];
        }
        
        if (count($this->idlePools[$key]) > 0) {
            return array_pop($this->idlePools[$key]);
        }
        
        if ($this->maxTotal >= 0 && $this->getNumActive() >= $this->maxTotal) {
            if ($this->blockWhenExhausted) {
                // Simulate waiting for an object
                while (count($this->idlePools[$key]) === 0) {
                    usleep(100);
                }
                return array_pop($this->idlePools[$key]);
            } else {
                throw new Exception("No available objects for key: $key");
            }
        }

        return new stdClass(); // Simulate creation of a new object
    }
    
    public function returnObject($key, $object) {
        if (!isset($this->idlePools[$key])) {
            $this->idlePools[$key] = [];
        }
        if (count($this->idlePools[$key]) < $this->maxTotalPerKey || $this->maxTotalPerKey < 0) {
            $this->idlePools[$key][] = $object;
        }
    }
    
    public function getNumActive() {
        return array_sum(array_map('count', $this->idlePools));
    }
}

$pool = new GenericKeyedObjectPool();
$key = 'exampleKey';
$object1 = $pool->borrowObject($key);
$pool->returnObject($key, $object1);
$object2 = $pool->borrowObject($key);
$pool->returnObject($key, $object2);
--EXPECT--
