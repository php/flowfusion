--TEST--
Translated seed (inferredbugs-java: GCViewer/1)
--FILE--
<?php

class IntData {
    private $data = [];

    public function add($value) {
        $this->data[] = $value;
    }

    public function getN() {
        return count($this->data);
    }

    public function getMin() {
        return min($this->data);
    }

    public function getMax() {
        return max($this->data);
    }

    public function average() {
        return array_sum($this->data) / count($this->data);
    }

    public function standardDeviation() {
        $mean = $this->average();
        $sum = 0;
        foreach ($this->data as $value) {
            $sum += ($value - $mean) ** 2;
        }
        return sqrt($sum / count($this->data));
    }

    public function getSum() {
        return array_sum($this->data);
    }
}

function main($filename) {
    $performanceData = new IntData();
    for ($i = 0; $i < 50; $i++) {
        $start = microtime(true);
        $dataReader = fopen($filename, 'r');
        while (!feof($dataReader)) {
            fgets($dataReader);
        }
        fclose($dataReader);
        $performanceData->add((int)((microtime(true) - $start) * 1000));
    }

    printIntData($filename, $performanceData);
}

function printIntData($filename, $data) {
    printf("results for %s in ms: %d runs\nmin, max, avg, stddev, sum\n%d, %d, %f, %f, %d",
        $filename, $data->getN(), 
        $data->getMin(), $data->getMax(), 
        $data->average(), $data->standardDeviation(), 
        $data->getSum());
}

if ($argc > 1) {
    main($argv[1]);
}
--EXPECT--
