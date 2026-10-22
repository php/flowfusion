--TEST--
Translated seed (inferredbugs-java: commons-pool/17)
--FILE--
<?php
class ObjectPool {
    private $maxActive = 8;
    private $maxIdle = 8;
    private $numActive = 0;
    private $pool = [];
    private $whenExhaustedAction = 1; // 0: fail, 1: block, 2: grow

    public function borrowObject() {
        if ($this->numActive >= $this->maxActive) {
            if ($this->whenExhaustedAction === 0) {
                throw new Exception("Pool exhausted");
            } elseif ($this->whenExhaustedAction === 1) {
                while ($this->numActive >= $this->maxActive) {
                    usleep(100); // block until space is available
                }
            } else {
                // grow the pool
            }
        }

        if (count($this->pool) > 0) {
            $obj = array_pop($this->pool);
            $this->numActive++;
            return $obj;
        }

        $obj = new stdClass(); // Simulating object creation
        $this->numActive++;
        return $obj;
    }

    public function returnObject($obj) {
        if ($this->numActive <= 0) {
            throw new Exception("No active objects to return");
        }
        if (count($this->pool) < $this->maxIdle) {
            $this->pool[] = $obj;
        }
        $this->numActive--;
    }
}

$pool = new ObjectPool();
try {
    $obj1 = $pool->borrowObject();
    $pool->returnObject($obj1);
    $obj2 = $pool->borrowObject();
} catch (Exception $e) {
    echo $e->getMessage();
}
--EXPECT--
