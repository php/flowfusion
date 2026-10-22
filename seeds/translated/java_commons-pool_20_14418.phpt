--TEST--
Translated seed (inferredbugs-java: commons-pool/20)
--FILE--
<?php

class PooledObject {
    private $object = null;
    private $state = 'IDLE';
    private $createTime;
    private $lastBorrowTime;
    private $lastReturnTime;

    public function __construct($object) {
        $this->object = $object;
        $this->createTime = $this->lastBorrowTime = $this->lastReturnTime = round(microtime(true) * 1000);
    }
    
    public function getObject() {
        return $this->object;
    }

    public function getCreateTime() {
        return $this->createTime;
    }

    public function getActiveTimeMillis() {
        return max(0, $this->lastReturnTime - $this->lastBorrowTime);
    }

    public function getIdleTimeMillis() {
        return round(microtime(true) * 1000) - $this->lastReturnTime;
    }

    public function getLastBorrowTime() {
        return $this->lastBorrowTime;
    }

    public function getLastReturnTime() {
        return $this->lastReturnTime;
    }

    public function allocate() {
        if ($this->state === 'IDLE') {
            $this->state = 'ALLOCATED';
            $this->lastBorrowTime = round(microtime(true) * 1000);
            return true;
        }
        return false;
    }

    public function deallocate() {
        if ($this->state === 'ALLOCATED') {
            $this->state = 'IDLE';
            $this->lastReturnTime = round(microtime(true) * 1000);
            return true;
        }
        return false;
    }

    public function compareTo($other) {
        $lastActiveDiff = $this->getLastReturnTime() - $other->getLastReturnTime();
        if ($lastActiveDiff === 0) {
            return spl_object_id($this) - spl_object_id($other);
        }
        return max(PHP_INT_MIN, min($lastActiveDiff, PHP_INT_MAX));
    }
}

$pooledObject1 = new PooledObject("Object 1");
$pooledObject2 = new PooledObject("Object 2");

$pooledObject1->allocate();
usleep(500);
$pooledObject2->allocate();
$pooledObject1->deallocate();
usleep(500);
$pooledObject2->deallocate();

$comparisonResult = $pooledObject1->compareTo($pooledObject2);
?>
--EXPECT--
