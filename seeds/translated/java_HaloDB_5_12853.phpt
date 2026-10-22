--TEST--
Translated seed (inferredbugs-java: HaloDB/5)
--FILE--
<?php

class Record {
    private $key;
    private $value;

    public function __construct($key, $value) {
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey() {
        return $this->key;
    }

    public function getValue() {
        return $this->value;
    }
}

class HaloDB {
    private $data = [];

    public function delete($key) {
        unset($this->data[$key]);
    }

    public function put($key, $value) {
        $this->data[$key] = $value;
    }

    public function get($key) {
        return $this->data[$key] ?? null;
    }
    
    public function close() {
        // Placeholder for close logic
    }
    
    public function listDataFileIds() {
        return [1]; // Simulating that 1 file id remains
    }
}

function insertRandomRecords($db, $noOfRecords) {
    $records = [];
    for ($i = 0; $i < $noOfRecords; $i++) {
        $key = bin2hex(random_bytes(10));
        $value = bin2hex(random_bytes(5));
        $db->put($key, $value);
        $records[] = new Record($key, $value);
    }
    return $records;
}

function testDeleteAllRecords() {
    $directory = 'tmp/HaloDBDeletionTest/testDeleteAllRecords';
    $db = new HaloDB();

    $noOfRecords = 10000;
    $records = insertRandomRecords($db, $noOfRecords);

    foreach ($records as $r) {
        $db->delete($r->getKey());
    }

    foreach ($records as $r) {
        if ($db->get($r->getKey()) !== null) {
            exit(1); // Simulate a failure
        }
    }

    if (count($db->listDataFileIds()) !== 1) {
        exit(1); // Simulate a failure
    }
}

testDeleteAllRecords();
--EXPECT--
