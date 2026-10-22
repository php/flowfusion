--TEST--
Translated seed (inferredbugs-java: HaloDB/10)
--FILE--
<?php

class HaloDBException extends Exception {}

class HaloDBInternal {
    private $currentWriteFile = null;
    private $inputData = [];
    private $nextSequenceNumber = 0;

    public function __construct() {}

    public static function open($directory, $options) {
        $dbInternal = new self();
        // Simulate the opening of a database by simply initializing values
        $dbInternal->inputData = [];
        return $dbInternal;
    }

    public function put($key, $value) {
        if (strlen($key) > 127) {
            throw new HaloDBException("Key length cannot exceed 127");
        }
        
        $record = ['key' => $key, 'value' => $value, 'sequenceNumber' => $this->getNextSequenceNumber()];
        $this->inputData[$key] = $record;
    }

    public function get($key) {
        return isset($this->inputData[$key]) ? $this->inputData[$key]['value'] : null;
    }

    public function getNextSequenceNumber() {
        return $this->nextSequenceNumber++;
    }
}

// Main execution
$db = HaloDBInternal::open('/path/to/directory', []);
$db->put('testKey1', 'testValue1');
$value = $db->get('testKey1');
echo $value; // Output: testValue1
$db->put(str_repeat('x', 128), 'testValue2'); // This will trigger an exception due to key length exceeding the limit

?>
--EXPECT--
