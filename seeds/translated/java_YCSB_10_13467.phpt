--TEST--
Translated seed (inferredbugs-java: YCSB/10)
--FILE--
<?php
class Database {
    private $data = [];

    public function insert($key, $value) {
        $this->data[$key] = $value;
    }

    public function delete($key) {
        unset($this->data[$key]);
    }

    public function read($key) {
        return $this->data[$key] ?? null;
    }

    public function update($key, $value) {
        if (isset($this->data[$key])) {
            $this->data[$key] = $value;
        }
    }

    public function scan($startKey, $count) {
        $results = [];
        $keys = array_keys($this->data);
        $startIndex = array_search($startKey, $keys);
        if ($startIndex === false) { return $results; }
        
        for ($i = $startIndex; $i < min($startIndex + $count, count($keys)); $i++) {
            $results[$keys[$i]] = $this->data[$keys[$i]];
        }
        return $results;
    }
}

$db = new Database();
$db->insert("1", "value1");
$db->insert("2", "value2");
$db->insert("3", "value3");
$db->insert("4", "value4");
$db->delete("2");
$db->update("1", "updated_value1");
$result = $db->read("1");
$scanResult = $db->scan("1", 2);
?>
--EXPECT--
