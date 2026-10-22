--TEST--
Translated seed (inferredbugs-java: datumbox-framework/8)
--FILE--
<?php

class Dataset {
    const YColumnName = 'Y';
    private $records = [];
    private $columns = [];
    
    public function addRecord($x, $y) {
        $this->records[] = new Record($x, $y);
        foreach ($x as $key => $value) {
            $this->columns[$key] = 'NUMERICAL';
        }
    }

    public function get($index) {
        return $this->records[$index];
    }

    public function isEmpty() {
        return empty($this->records);
    }

    public function getColumns() {
        return $this->columns;
    }

    public function extractYValues() {
        return array_map(function($record) {
            return $record->getY();
        }, $this->records);
    }

    public function count() {
        return count($this->records);
    }
}

class Record {
    private $x;
    private $y;

    public function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX() {
        return $this->x;
    }

    public function getY() {
        return $this->y;
    }

    public function setY($y) {
        $this->y = $y;
    }
}

function fitAndNormalizeY($data, &$minColumnValues, &$maxColumnValues) {
    if ($data->isEmpty()) return;

    $yValues = $data->extractYValues();
    $min = min($yValues);
    $max = max($yValues);

    $minColumnValues[Dataset::YColumnName] = $min;
    $maxColumnValues[Dataset::YColumnName] = $max;

    foreach ($data->records as $record) {
        $value = $record->getY();
        if ($value !== null) {
            $normalizedValue = ($value - $min) / ($max - $min);
            $record->setY($normalizedValue);
        }
    }
}

$data = new Dataset();
$data->addRecord(['feature1' => 1, 'feature2' => 2], 3);
$data->addRecord(['feature1' => 4, 'feature2' => 5], 6);

$minColumnValues = [];
$maxColumnValues = [];

fitAndNormalizeY($data, $minColumnValues, $maxColumnValues);
--EXPECT--
