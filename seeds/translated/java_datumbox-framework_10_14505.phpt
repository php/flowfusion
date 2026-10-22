--TEST--
Translated seed (inferredbugs-java: datumbox-framework/10)
--FILE--
<?php

class AssociativeArray extends ArrayObject {}

class Record {
    public $data;
    public function __construct($data) {
        $this->data = $data;
    }
}

class Dataset {
    private $records = [];
    
    public function add($record) {
        $this->records[] = $record;
    }
    
    public function getColumns() {
        $columns = [];
        foreach ($this->records as $record) {
            foreach ($record->data as $key => $value) {
                $columns[$key] = $value;
            }
        }
        return $columns;
    }
}

class TFIDF {
    private $dbName;

    public function __construct($dbName) {
        $this->dbName = $dbName;
    }

    public function fit($trainingData) {}

    public function transform($trainingData) {
        // Simulating feature selection
    }

    public function erase() {
        $this->dbName = null;
    }
}

$dbName = "TFIDFTestDB";

$trainingData = new Dataset();

$xData1 = new AssociativeArray();
$xData1["important1"] = 2.0;
$xData1["important2"] = 3.0;
$xData1["stopword1"] = 10.0;
$xData1["stopword2"] = 4.0;
$xData1["stopword3"] = 8.0;
$trainingData->add(new Record($xData1));

$xData2 = new AssociativeArray();
$xData2["important1"] = 2.0;
$xData2["important3"] = 5.0;
$xData2["stopword1"] = 10.0;
$xData2["stopword2"] = 2.0;
$xData2["stopword3"] = 4.0;
$trainingData->add(new Record($xData2));

$xData3 = new AssociativeArray();
$xData3["important2"] = 2.0;
$xData3["important3"] = 5.0;
$xData3["stopword1"] = 10.0;
$xData3["stopword2"] = 2.0;
$xData3["stopword3"] = 4.0;
$trainingData->add(new Record($xData3));

$instance = new TFIDF($dbName);
$instance->fit($trainingData);
$instance = new TFIDF($dbName);
$instance->transform($trainingData);

$result = array_keys($trainingData->getColumns());
$expectedResult = ["important1", "important2", "important3"];
sort($result);
sort($expectedResult);

$instance->erase();
--EXPECT--
