--TEST--
Translated seed (inferredbugs-java: commons-pool/18)
--FILE--
<?php

class PooledObject {
    private $object = null;
    private $state = 'IDLE';
    private $lastActiveTime;

    public function __construct($object) {
        $this->object = $object;
        $this->lastActiveTime = microtime(true) * 1000;
    }

    public function getObject() {
        return $this->object;
    }

    public function getIdleTimeMillis() {
        return microtime(true) * 1000 - $this->lastActiveTime;
    }

    public function getLastActiveTime() {
        return $this->lastActiveTime;
    }

    public function compareTo($other) {
        $lastActiveDiff = $this->getLastActiveTime() - $other->getLastActiveTime();
        if ($lastActiveDiff == 0) {
            return spl_object_id($this) - spl_object_id($other);
        }
        return min(max($lastActiveDiff, PHP_INT_MIN), PHP_INT_MAX);
    }

    public function startEvictionTest() {
        if ($this->state == 'IDLE') {
            $this->state = 'MAINTAIN_EVICTION';
            return true;
        }
        return false;
    }

    public function endEvictionTest(&$idleQueue) {
        if ($this->state == 'MAINTAIN_EVICTION') {
            $this->state = 'IDLE';
            return true;
        }
        return false;
    }

    public function allocate() {
        if ($this->state == 'IDLE') {
            $this->state = 'ALLOCATED';
            return true;
        }
        return false;
    }

    public function deallocate() {
        if ($this->state == 'ALLOCATED') {
            $this->state = 'IDLE';
            $this->lastActiveTime = microtime(true) * 1000;
            return true;
        }
        return false;
    }
}

$pooledObject1 = new PooledObject("Object1");
$pooledObject2 = new PooledObject("Object2");
$pooledObject1->allocate();
usleep(100);
$pooledObject2->allocate();
$comparisonResult = $pooledObject1->compareTo($pooledObject2);
--EXPECT--
