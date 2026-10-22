--TEST--
Translated seed (inferredbugs-java: commons-pool/7)
--FILE--
<?php

class GenericKeyedObjectPool {
    private $maxActive = 8;
    private $maxIdle = 8;
    private $whenExhaustedAction = 1; // 0 = fail, 1 = block, 2 = grow
    private $allocationQueue = [];
    private $poolMap = [];
    private $totalActive = 0;
    private $totalIdle = 0;

    public function borrowObject($key) {
        while (true) {
            $this->allocate();
            if (isset($this->allocationQueue[$key])) {
                $pair = array_shift($this->allocationQueue[$key]);
                if ($pair) {
                    $this->totalActive++;
                    return $pair;
                }
            }
            if ($this->whenExhaustedAction === 0) {
                throw new Exception("Pool exhausted");
            } elseif ($this->whenExhaustedAction === 1) {
                usleep(100000); // wait and retry
            } elseif ($this->whenExhaustedAction === 2) {
                $this->poolMap[$key][] = new stdClass(); // simulate object creation
                $this->totalIdle++; 
            }
        }
    }

    public function returnObject($key, $obj) {
        if ($this->totalIdle < $this->maxIdle) {
            $this->poolMap[$key][] = $obj;
            $this->totalIdle++;
        } else {
            // Simulate object destruction
        }
        $this->totalActive--;
    }

    private function allocate() {
        foreach ($this->poolMap as $key => $pool) {
            while (count($pool) > 0 && $this->totalActive < $this->maxActive) {
                $this->allocationQueue[$key][] = array_shift($this->poolMap[$key]);
            }
        }
    }
}

function main() {
    $pool = new GenericKeyedObjectPool();
    $key = "exampleKey";

    // Simulate borrowing and returning objects
    try {
        $obj = $pool->borrowObject($key);
        $pool->returnObject($key, $obj);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

main();
--EXPECT--
