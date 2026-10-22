--TEST--
Translated seed (inferredbugs-java: commons-pool/30)
--FILE--
<?php

class PooledObject {
    private $object;
    private $state = 'IDLE';
    private $createTime;
    private $lastBorrowTime;
    private $lastReturnTime;
    private $logAbandoned = false;
    private $borrowedBy = null;
    
    public function __construct($object) {
        $this->object = $object;
        $this->createTime = $this->lastBorrowTime = $this->lastReturnTime = microtime(true) * 1000;
    }
    
    public function getObject() {
        return $this->object;
    }
    
    public function getCreateTime() {
        return $this->createTime;
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
            $this->lastBorrowTime = microtime(true) * 1000;
            return true;
        }
        return false;
    }
    
    public function deallocate() {
        if ($this->state === 'ALLOCATED') {
            $this->state = 'IDLE';
            $this->lastReturnTime = microtime(true) * 1000;
            return true;
        }
        return false;
    }

    public function startEvictionTest() {
        if ($this->state === 'IDLE') {
            $this->state = 'EVICTION';
            return true;
        }
        return false;
    }
    
    public function endEvictionTest() {
        if ($this->state === 'EVICTION') {
            $this->state = 'IDLE';
            return true;
        }
        return false;
    }
    
    public function getState() {
        return $this->state;
    }
    
    public function markAbandoned() {
        $this->state = 'ABANDONED';
    }
}

$obj = new PooledObject(new stdClass());
$obj->allocate();
usleep(100);
$obj->deallocate();
echo $obj->getLastBorrowTime() . "\n";
echo $obj->getLastReturnTime() . "\n";
--EXPECT--
