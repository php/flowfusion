--TEST--
Translated seed (inferredbugs-java: commons-dbutils/2)
--FILE--
<?php

class TestBean {
    public $one;
    public $two;
    public $three;
    public $doNotSet = "not set";
    public $intTest = 3;
    public $integerTest = 4;
    public $nullObjectTest = null;
    public $nullPrimitiveTest = 0;
    public $notDate;

    public function __construct($data) {
        foreach ($data as $key => $value) {
            if (property_exists($this, strtolower($key))) {
                $this->{strtolower($key)} = $value;
            }
        }
        $this->notDate = "2023-10-01"; // Example date for parsing
    }
}

class BasicRowProcessor {
    public function toArray($resultSet) {
        return [$resultSet['One'], $resultSet['Two'], $resultSet['three']];
    }

    public function toBean($resultSet) {
        return new TestBean($resultSet);
    }

    public function toBeanList($resultSetList) {
        $beans = [];
        foreach ($resultSetList as $resultSet) {
            $beans[] = $this->toBean($resultSet);
        }
        return $beans;
    }

    public function toMap($resultSet) {
        return array_change_key_case($resultSet, CASE_LOWER);
    }
}

$processor = new BasicRowProcessor();
$data = [
    ['One' => '4', 'Two' => '5', 'three' => '6'],
    ['One' => '4', 'Two' => '5', 'three' => '6'],
];

$rowCount = 0;
$a = null;
foreach ($data as $resultSet) {
    $a = $processor->toArray($resultSet);
    $rowCount++;
}
$rows = count($data);
$b = null;

foreach ($data as $resultSet) {
    $b = $processor->toBean($resultSet);
    $rowCount++;
}
$list = $processor->toBeanList($data);
$m = null;

foreach ($data as $resultSet) {
    $m = $processor->toMap($resultSet);
    $rowCount++;
}
--EXPECT--
