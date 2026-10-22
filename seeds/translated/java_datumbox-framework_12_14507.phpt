--TEST--
Translated seed (inferredbugs-java: datumbox-framework/12)
--FILE--
<?php

class DummyMinMaxTransformer {
    
    protected $minColumnValues = [];
    protected $maxColumnValues = [];

    public function fitX($data) {
        foreach ($data['X'] as $column => $values) {
            if (is_numeric($values[0])) {
                $this->minColumnValues[$column] = min($values);
                $this->maxColumnValues[$column] = max($values);
            }
        }
    }
    
    public function normalizeX(&$data) {
        foreach ($data['X'] as $rowIndex => $row) {
            foreach ($this->minColumnValues as $column => $min) {
                $max = $this->maxColumnValues[$column];
                if ($row[$column] === null) continue;
                if ($min === $max) {
                    $data['X'][$rowIndex][$column] = $min > 0.0 ? 1.0 : 0.0;
                } else {
                    $data['X'][$rowIndex][$column] = ($row[$column] - $min) / ($max - $min);
                }
            }
        }
    }
    
    public function denormalizeX(&$data) {
        foreach ($data['X'] as $rowIndex => $row) {
            foreach ($this->minColumnValues as $column => $min) {
                $max = $this->maxColumnValues[$column];
                if ($row[$column] === null) continue;
                if ($min === $max) {
                    $data['X'][$rowIndex][$column] = $min;
                } else {
                    $data['X'][$rowIndex][$column] = $row[$column] * ($max - $min) + $min;
                }
            }
        }
    }
}

$data = [
    'X' => [
        ['a' => 1, 'b' => 2],
        ['a' => 3, 'b' => 4],
        ['a' => 5, 'b' => 6],
    ]
];

$transformer = new DummyMinMaxTransformer();
$transformer->fitX($data);
$transformer->normalizeX($data);
$transformer->denormalizeX($data);
--EXPECT--
