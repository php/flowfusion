--TEST--
Translated seed (inferredbugs-java: commons-dbutils/4)
--FILE--
<?php

class TestBean {
    private $columnProcessorDoubleTest;

    public function __construct($doubleValue) {
        $this->columnProcessorDoubleTest = $doubleValue;
    }

    public function getColumnProcessorDoubleTest() {
        return $this->columnProcessorDoubleTest;
    }
}

$rows = 10;
$rowCount = 0;
$b = null;

for ($i = 0; $i < $rows; $i++) {
    $b = new TestBean(13.0);
    $rowCount++;
}

$rowCount === $rows;
$b->getColumnProcessorDoubleTest() === 13.0;

?>
--EXPECT--
