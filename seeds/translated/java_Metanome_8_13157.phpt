--TEST--
Translated seed (inferredbugs-java: Metanome/8)
--FILE--
<?php

class CsvFile {
    private $data;
    private $index;

    public function __construct($separator = ',') {
        $this->data = [];
        $this->index = 0;

        // Simulate reading a one-line CSV input
        $this->data[] = explode($separator, "value1,value2,value3");
    }

    public function hasNext() {
        return $this->index < count($this->data);
    }

    public function next() {
        if (!$this->hasNext()) {
            throw new Exception("No more elements");
        }
        return $this->data[$this->index++];
    }

    public function remove() {
        throw new Exception("UnsupportedOperationException");
    }
}

$csvFile = new CsvFile();

// Check hasNext
$hasNext = $csvFile->hasNext(); // Expected: true
$csvFile->next();
$hasNextAfterNext = $csvFile->hasNext(); // Expected: false

// Check next
$values = $csvFile->next(); // Expected: ['value1', 'value2', 'value3']

// Check remove
try {
    $csvFile->remove();
} catch (Exception $e) {
    // expected behavior
}

exit(0);
--EXPECT--
