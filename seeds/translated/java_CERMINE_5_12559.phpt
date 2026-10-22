--TEST--
Translated seed (inferredbugs-java: CERMINE/5)
--FILE--
<?php

class TrainingSample {
    private $label;
    private $features;

    public function __construct($label, $features) {
        $this->label = $label;
        $this->features = $features;
    }

    public function getLabel() {
        return $this->label;
    }

    public function getFeatures() {
        return $this->features;
    }
}

class FeatureVector {
    private $features;

    public function __construct($features) {
        $this->features = $features;
    }

    public function getFeatures() {
        return $this->features;
    }
}

function toLibSVM($trainingElements, $filePath) {
    $fileWriter = fopen($filePath, 'w');
    if ($fileWriter === false) {
        echo "Error: Unable to open file for writing.\n";
        return;
    }

    foreach ($trainingElements as $elem) {
        if ($elem->getLabel() === null) {
            continue;
        }
        fwrite($fileWriter, $elem->getLabel() . " ");

        $featureCounter = 1;
        foreach ($elem->getFeatures()->getFeatures() as $value) {
            fwrite($fileWriter, sprintf("%d:%.5f", $featureCounter++, $value) . " ");
        }
        fwrite($fileWriter, "\n");
    }
    fclose($fileWriter);
}

function main() {
    $trainingElements = [];
    $trainingElements[] = new TrainingSample(1, new FeatureVector([0.1, 0.2, 0.3]));
    $trainingElements[] = new TrainingSample(2, new FeatureVector([0.4, 0.5, 0.6]));
    $trainingElements[] = new TrainingSample(null, new FeatureVector([0.7, 0.8, 0.9]));

    toLibSVM($trainingElements, "output.dat");

    echo "Done.\n";
}

main();
--EXPECT--
