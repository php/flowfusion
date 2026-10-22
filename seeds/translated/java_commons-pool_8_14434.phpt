--TEST--
Translated seed (inferredbugs-java: commons-pool/8)
--FILE--
<?php

class GenericKeyedObjectPool {
    private $maxActive = 8;
    private $maxIdle = 8;
    private $whenExhaustedAction = 1; // 0 = fail, 1 = block, 2 = grow
    private $pool = [];

    public function borrowObject($key) {
        if (!isset($this->pool[$key])) {
            $this->pool[$key] = [];
        }
        if (count($this->pool[$key]) < $this->maxActive) {
            $object = new stdClass(); // Simulating object creation
            $this->pool[$key][] = $object;
            return $object;
        } else {
            switch ($this->whenExhaustedAction) {
                case 0: throw new Exception("No available objects");
                case 1: sleep(1); return $this->borrowObject($key); // Blocking
                case 2: 
                    $object = new stdClass(); // Simulating object creation
                    $this->pool[$key][] = $object;
                    return $object;
            }
        }
    }

    public function returnObject($key, $object) {
        if (isset($this->pool[$key]) && in_array($object, $this->pool[$key])) {
            $keyIndex = array_search($object, $this->pool[$key]);
            unset($this->pool[$key][$keyIndex]);
        }
    }
}

$pool = new GenericKeyedObjectPool();
$obj1 = $pool->borrowObject('key1');
$obj2 = $pool->borrowObject('key1');
$pool->returnObject('key1', $obj1);
$obj3 = $pool->borrowObject('key1');
--EXPECT--
