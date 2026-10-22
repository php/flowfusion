--TEST--
Translated seed (inferredbugs-java: commons-pool/25)
--FILE--
<?php
class GenericKeyedObjectPool {
    private $pool = [];
    private $maxTotalPerKey = 3;
    private $maxTotal = 10;
    private $blockWhenExhausted = true;

    public function borrowObject($key) {
        if (!isset($this->pool[$key])) {
            $this->pool[$key] = [];
        }
        if (count($this->pool[$key]) < $this->maxTotalPerKey) {
            $this->pool[$key][] = new stdClass();
            return end($this->pool[$key]);
        }

        if ($this->blockWhenExhausted) {
            while (count($this->pool[$key]) >= $this->maxTotalPerKey) {
                usleep(1000);
            }
        } else {
            throw new Exception("Pool exhausted");
        }
        return $this->borrowObject($key);
    }

    public function returnObject($key, $object) {
        if (!isset($this->pool[$key])) {
            throw new Exception("No pool for key");
        }
        array_push($this->pool[$key], $object);
    }
}

$pool = new GenericKeyedObjectPool();
$obj1 = $pool->borrowObject('key1');
$obj2 = $pool->borrowObject('key1');
$obj3 = $pool->borrowObject('key1'); // This should be the last object borrowed
$pool->returnObject('key1', $obj1);
$pool->returnObject('key1', $obj2);
$pool->returnObject('key1', $obj3); // Return the last object borrowed
--EXPECT--
