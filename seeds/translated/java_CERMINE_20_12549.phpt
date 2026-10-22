--TEST--
Translated seed (inferredbugs-java: CERMINE/20)
--FILE--
<?php

class FeatureVector {
    private $features = [];

    public function addFeature($name, $value) {
        $this->features[$name] = $value;
    }
}

class TrainingSample {
    private $featureVector;
    private $label;

    public function __construct($featureVector, $label) {
        $this->featureVector = $featureVector;
        $this->label = $label;
    }
}

class BxZoneLabel {
    const LABEL_0 = 0;
    const LABEL_1 = 1;
    const LABEL_2 = 2;
    const LABEL_3 = 3;
    
    public static function values() {
        return [self::LABEL_0, self::LABEL_1, self::LABEL_2, self::LABEL_3];
    }
}

class FeatureVectorBuilder {
    public function getFeatureNames() {
        return ["feature1", "feature2", "feature3"];
    }
}

function classifyZones($document) {
    foreach ($document as $zone) {
        $predicted = predictLabel($zone);
        $zone['label'] = $predicted;
    }
    return $document;
}

function loadProblem($path, $fvb) {
    $ret = [];
    $handle = fopen($path, "r");
    
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $parts = preg_split('/\s+/', trim($line));
            $label = BxZoneLabel::values()[$parts[0]];
            $fv = new FeatureVector();
            $featureNames = $fvb->getFeatureNames();
            for ($partIdx = 1; $partIdx < count($parts); ++$partIdx) {
                $subparts = explode(':', $parts[$partIdx]);
                $fv->addFeature($featureNames[$partIdx - 1], (double)$subparts[1]);
            }
            $sample = new TrainingSample($fv, $label);
            $ret[] = $sample;
        }
        fclose($handle);
    }
    return $ret;
}

function predictLabel($zone) {
    // Dummy prediction for demonstration purposes
    return BxZoneLabel::LABEL_0;
}

$document = [
    ['name' => 'zone1'],
    ['name' => 'zone2'],
    ['name' => 'zone3'],
];

$fvb = new FeatureVectorBuilder();
$classifiedDocument = classifyZones($document);
$samples = loadProblem('data.txt', $fvb);

?>
--EXPECT--
