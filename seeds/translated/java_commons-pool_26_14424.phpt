--TEST--
Translated seed (inferredbugs-java: commons-pool/26)
--FILE--
<?php

class ObjectPool {
    private $maxTotalPerKey = 8;
    private $maxTotal = -1;
    private $idleInstances = [];
    private $activeInstances = [];
    
    public function borrowObject($key) {
        if (isset($this->idleInstances[$key]) && count($this->idleInstances[$key]) > 0) {
            $object = array_pop($this->idleInstances[$key]);
            $this->activeInstances[$key][] = $object;
            return $object;
        } elseif ($this->canCreateNewInstance($key)) {
            $object = new stdClass();
            $this->activeInstances[$key][] = $object;
            return $object;
        }
        throw new Exception("No available objects for key: $key");
    }

    public function returnObject($key, $object) {
        if (($keyInstances = $this->activeInstances[$key]) && in_array($object, $keyInstances)) {
            $this->idleInstances[$key][] = $object;
            $this->activeInstances[$key] = array_values(array_diff($keyInstances, [$object]));
        } else {
            throw new Exception("Object not borrowed from key: $key");
        }
    }

    private function canCreateNewInstance($key) {
        if ($this->maxTotalPerKey > 0 && (!isset($this->activeInstances[$key]) || count($this->activeInstances[$key]) >= $this->maxTotalPerKey)) {
            return false;
        }
        if ($this->maxTotal > 0 && count($this->activeInstances) >= $this->maxTotal) {
            return false;
        }
        return true;
    }
}

$pool = new ObjectPool();
$key = "testKey";

$object1 = $pool->borrowObject($key);
$pool->returnObject($key, $object1);
$object2 = $pool->borrowObject($key);
$pool->returnObject($key, $object2);
--EXPECT--
