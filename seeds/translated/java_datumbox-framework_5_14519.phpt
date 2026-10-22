--TEST--
Translated seed (inferredbugs-java: datumbox-framework/5)
--FILE--
<?php

class Dataset {
    private $data = [];
    
    public function add($record) {
        $this->data[] = $record;
    }
    
    public function getData() {
        return $this->data;
    }
}

class Record {
    private $features;
    private $label;

    public function __construct($features, $label) {
        $this->features = $features;
        $this->label = $label;
    }

    public function getFeatures() {
        return $this->features;
    }

    public function getLabel() {
        return $this->label;
    }
}

class BootstrapAggregating {
    private $model;

    public function fit($data) {
        // Simulated fitting logic
        $this->model = "fitted_model";
    }
    
    public function validate($data) {
        // Simulated validation logic
        foreach ($data->getData() as $record) {
            $prediction = "yes"; // Simulate prediction
            echo "Predicted: $prediction, Actual: " . $record->getLabel() . "\n";
        }
    }
}

$trainingData = new Dataset();
$trainingData->add(new Record(["red", "sports", "domestic"], "yes"));
$trainingData->add(new Record(["red", "sports", "domestic"], "no"));
$trainingData->add(new Record(["red", "sports", "domestic"], "yes"));
$trainingData->add(new Record(["yellow", "sports", "domestic"], "no"));
$trainingData->add(new Record(["yellow", "sports", "imported"], "yes"));
$trainingData->add(new Record(["yellow", "suv", "imported"], "no"));
$trainingData->add(new Record(["yellow", "suv", "imported"], "yes"));
$trainingData->add(new Record(["yellow", "suv", "domestic"], "no"));
$trainingData->add(new Record(["red", "suv", "imported"], "no"));
$trainingData->add(new Record(["red", "sports", "imported"], "yes"));

$validationData = new Dataset();
$validationData->add(new Record(["red", "suv", "domestic"], "no"));

$instance = new BootstrapAggregating();
$instance->fit($trainingData);
$instance->validate($validationData);
--EXPECT--
