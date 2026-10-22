--TEST--
Translated seed (inferredbugs-java: commons-pool/13)
--FILE--
<?php

class ObjectPool {
    const WHEN_EXHAUSTED_FAIL = 0;
    const WHEN_EXHAUSTED_BLOCK = 1;
    const WHEN_EXHAUSTED_GROW = 2;

    private $maxActive = 8;
    private $whenExhaustedAction = self::WHEN_EXHAUSTED_BLOCK;
    private $maxWait = -1;
    private $pool = [];
    private $active = 0;

    public function borrowObject() {
        if ($this->active < $this->maxActive) {
            $object = $this->createObject();
            $this->active++;
            return $object;
        } elseif ($this->whenExhaustedAction == self::WHEN_EXHAUSTED_GROW) {
            $object = $this->createObject();
            $this->active++;
            return $object;
        } elseif ($this->whenExhaustedAction == self::WHEN_EXHAUSTED_BLOCK) {
            $startTime = microtime(true);
            while ($this->active >= $this->maxActive) {
                if ($this->maxWait > 0 && (microtime(true) - $startTime) * 1000 >= $this->maxWait) {
                    throw new Exception("Timeout waiting for idle object");
                }
                usleep(100);
            }
            return $this->borrowObject();
        }
        throw new Exception("Pool exhausted");
    }

    public function returnObject($object) {
        $this->active--;
        $this->pool[] = $object;
        if ($this->active < 0) {
            throw new Exception("Active count went negative");
        }
    }

    private function createObject() {
        return new stdClass(); // Simulates object creation
    }
}

$pool = new ObjectPool();
$object1 = $pool->borrowObject();
$pool->returnObject($object1);
$object2 = $pool->borrowObject();
$pool->returnObject($object2);
--EXPECT--
