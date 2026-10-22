--TEST--
Translated seed (inferredbugs-java: YCSB/14)
--FILE--
<?php

class Test {
    private $bufferedMutator = null;
    private $currentTable = null;
    private $tableName = "";
    private $debug = false;
    private $columnFamily = "";
    private $data = [];

    public function init($props) {
        $this->columnFamily = $props['columnfamily'] ?? '';
        if ($this->columnFamily === '') {
            die("Error, must specify a columnfamily");
        }
        $this->debug = $props['debug'] ?? false;

        // Mocking database initialization
        $this->data['test_table'] = [];
    }

    public function insert($table, $key, $values) {
        if ($this->tableName !== $table) {
            $this->currentTable = null;
            $this->tableName = $table;
        }
        $this->data[$table][$key] = $values;
    }

    public function read($table, $key) {
        if ($this->tableName !== $table) {
            $this->currentTable = null;
            $this->tableName = $table;
        }
        return $this->data[$table][$key] ?? null;
    }

    public function delete($table, $key) {
        if ($this->tableName !== $table) {
            $this->currentTable = null;
            $this->tableName = $table;
        }
        unset($this->data[$table][$key]);
    }

    public function run() {
        $this->init(['columnfamily' => 'cf', 'debug' => true]);
        $this->insert('test_table', 'row1', ['field1' => 'value1']);
        $this->insert('test_table', 'row2', ['field1' => 'value2']);
        $this->delete('test_table', 'row1');
        $result = $this->read('test_table', 'row1');
        var_dump($result); // Should print NULL since row1 has been deleted
    }
}

$test = new Test();
$test->run();
--EXPECT--
