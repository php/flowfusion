--TEST--
Translated seed (inferredbugs-java: commons-pool/27)
--FILE--
<?php
class GenericKeyedObjectPool {
    private $pools = [];
    private $maxTotalPerKey = 8;
    private $maxTotal = -1;
    private $maxIdlePerKey = 8;
    private $blockWhenExhausted = true;
    private $maxWait = -1;

    public function borrowObject($key) {
        if (!isset($this->pools[$key])) {
            $this->pools[$key] = ['idle' => [], 'active' => 0];
        }
        if (count($this->pools[$key]['idle']) > 0) {
            $object = array_pop($this->pools[$key]['idle']);
            $this->pools[$key]['active']++;
            return $object;
        } elseif ($this->maxTotalPerKey === -1 || $this->pools[$key]['active'] < $this->maxTotalPerKey) {
            $object = new stdClass(); // Simulate object creation
            $this->pools[$key]['active']++;
            return $object;
        } elseif ($this->blockWhenExhausted) {
            $startTime = microtime(true);
            while ($this->pools[$key]['active'] >= $this->maxTotalPerKey) {
                if ($this->maxWait >= 0 && (microtime(true) - $startTime) * 1000 > $this->maxWait) {
                    throw new Exception('Timeout waiting for idle object');
                }
                usleep(100); // Wait a bit
            }
            return $this->borrowObject($key); // Retry
        }
        throw new Exception('Pool exhausted');
    }

    public function returnObject($key, $object) {
        if (!isset($this->pools[$key])) {
            throw new Exception('Invalid pool key');
        }
        array_push($this->pools[$key]['idle'], $object);
        $this->pools[$key]['active']--;
        if ($this->maxIdlePerKey > 0 && count($this->pools[$key]['idle']) > $this->maxIdlePerKey) {
            array_pop($this->pools[$key]['idle']); // Discard the oldest
        }
    }
}

$pool = new GenericKeyedObjectPool();
$key = 'myKey';
$object1 = $pool->borrowObject($key);
$object2 = $pool->borrowObject($key);
$pool->returnObject($key, $object1);
$pool->returnObject($key, $object2);
--EXPECT--
