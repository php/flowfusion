--TEST--
Translated seed (inferredbugs-java: blynk-server/9)
--FILE--
<?php

$reportingFolder = sys_get_temp_dir() . '/reporting';

function createReportingFolder($path) {
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

function generateFilename($id, $pinType, $pin, $graphType) {
    return "{$graphType}_{$id}_{$pinType}_{$pin}.data";
}

function getTS() {
    return strtotime("Aug 10, 2015 12:10:56") * 1000;
}

class AggregationValue {
    private $value = 0;

    public function update($value) {
        $this->value = $value;
    }

    public function get() {
        return $this->value;
    }
}

class AggregationKey {
    public function __construct($name, $id, $pinType, $pin, $timestamp) {}
}

class StorageWorker {
    private $reportingFolder;

    public function __construct($reportingFolder) {
        $this->reportingFolder = $reportingFolder;
    }

    public function run($map) {
        foreach ($map as $key => $value) {
            $file = "{$this->reportingFolder}/{$key}/" . generateFilename(1, 'ANALOG', 1, 'HOURLY');
            file_put_contents($file, pack("d", $value->get()));
        }
    }
}

function testStore() {
    global $reportingFolder;

    createReportingFolder($reportingFolder);
    
    $map = [];
    $ts = getTS() / 3600000;

    $aggregationKey = new AggregationKey("test", 1, 'ANALOG', 1, $ts);
    $aggregationValue = new AggregationValue();
    $aggregationValue->update(100);
    $map[$aggregationKey] = $aggregationValue;

    $aggregationKey2 = new AggregationKey("test", 1, 'ANALOG', 1, $ts - 1);
    $aggregationValue2 = new AggregationValue();
    $aggregationValue2->update(150.54);
    $map[$aggregationKey2] = $aggregationValue2;

    $storageWorker = new StorageWorker($reportingFolder);
    $storageWorker->run($map);
}

testStore();

?>
--EXPECT--
