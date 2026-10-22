--TEST--
Translated seed (inferredbugs-java: commons-dbutils/3)
--FILE--
<?php

class BasicRowProcessor {
    public function toArray($resultSet) {
        return array_values($resultSet);
    }
    
    public function toBean($resultSet, $className) {
        return new $className(...array_values($resultSet));
    }
    
    public function toBeanList($resultSet, $className) {
        $list = [];
        foreach ($resultSet as $row) {
            $list[] = new $className(...array_values($row));
        }
        return $list;
    }
    
    public function toMap($resultSet) {
        return array_change_key_case(array_flip(array_keys($resultSet)), CASE_LOWER);
    }
}

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

    public function __construct($one, $two, $three, $notDate) {
        $this->one = $one;
        $this->two = $two;
        $this->three = $three;
        $this->notDate = $notDate;
    }
}

$processor = new BasicRowProcessor();
$resultSet = [
    ["One" => "4", "two" => "5", "THREE" => "6", "notDate" => "not a date"],
    ["One" => "7", "two" => "8", "THREE" => "9", "notDate" => "not a date"]
];

$rowCount = 0;
foreach ($resultSet as $row) {
    $a = $processor->toArray($row);
    $rowCount++;
}

$list = $processor->toBeanList($resultSet, TestBean::class);
$b = $list[0];

$m = $processor->toMap($resultSet[0]);
--EXPECT--
