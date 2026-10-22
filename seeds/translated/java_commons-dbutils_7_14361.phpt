--TEST--
Translated seed (inferredbugs-java: commons-dbutils/7)
--FILE--
<?php

class TestBean {
    private $one;
    private $two;
    private $three;
    private $doNotSet;

    public function __construct($one, $two, $three, $doNotSet) {
        $this->one = $one;
        $this->two = $two;
        $this->three = $three;
        $this->doNotSet = $doNotSet;
    }

    public function getOne() {
        return $this->one;
    }

    public function getTwo() {
        return $this->two;
    }

    public function getThree() {
        return $this->three;
    }

    public function getDoNotSet() {
        return $this->doNotSet;
    }
}

class BeanListHandler {
    private $className;

    public function __construct($className) {
        $this->className = $className;
    }

    public function handle($resultSet) {
        $results = [];
        foreach ($resultSet as $row) {
            $results[] = new $this->className($row[0], $row[1], $row[2], $row[3]);
        }
        return $results;
    }
}

$resultSet = [
    ['4', '5', '6', 'not set']
];

$handler = new BeanListHandler(TestBean::class);
$results = $handler->handle($resultSet);

$row = null;
foreach ($results as $result) {
    $row = $result;
}

$row->getOne();
$row->getTwo();
$row->getThree();
$row->getDoNotSet();

$emptyResultSet = [];
$emptyHandler = new BeanListHandler(TestBean::class);
$emptyResults = $emptyHandler->handle($emptyResultSet);
--EXPECT--
