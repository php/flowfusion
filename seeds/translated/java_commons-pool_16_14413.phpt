--TEST--
Translated seed (inferredbugs-java: commons-pool/16)
--FILE--
<?php

class GenericObjectPool {
    private $maxActive = 8;
    private $pool = [];
    private $numActive = 0;
    private $whenExhaustedAction = 1; // 1 = BLOCK
    private $maxWait = -1;

    public function borrowObject() {
        if ($this->numActive >= $this->maxActive) {
            switch ($this->whenExhaustedAction) {
                case 0: // FAIL
                    throw new Exception("Pool exhausted");
                case 1: // BLOCK
                    $startTime = microtime(true);
                    while ($this->numActive >= $this->maxActive) {
                        if ($this->maxWait > 0 && (microtime(true) - $startTime) > $this->maxWait) {
                            throw new Exception("Timeout waiting for idle object");
                        }
                        usleep(100); // Sleep for a while before checking again
                    }
                    break;
                case 2: // GROW
                    // Allow creation of new object
                    break;
            }
        }
        $obj = new stdClass(); // Simulating object creation
        $this->pool[] = $obj;
        $this->numActive++;
        return $obj;
    }

    public function returnObject($obj) {
        $this->numActive--;
        // In a real scenario, validate and possibly destroy the object
    }
}

function main() {
    $pool = new GenericObjectPool();

    for ($i = 0; $i < 10; $i++) {
        try {
            $obj = $pool->borrowObject();
            // Simulate usage
            $pool->returnObject($obj);
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}

main();
--EXPECT--
