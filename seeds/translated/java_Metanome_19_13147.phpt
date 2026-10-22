--TEST--
Translated seed (inferredbugs-java: Metanome/19)
--FILE--
<?php

class Algorithm {
    public $name;
    public $isInd = false;
    public $isUcc = false;
    public $isFd = false;
    public $isBasicStat = false;

    public function __construct($name) {
        $this->name = $name;
    }

    public function setInd($value) {
        $this->isInd = $value;
        return $this;
    }

    public function setUcc($value) {
        $this->isUcc = $value;
        return $this;
    }

    public function setFd($value) {
        $this->isFd = $value;
        return $this;
    }

    public function setBasicStat($value) {
        $this->isBasicStat = $value;
        return $this;
    }

    public function store() {
        // Simulate storing the algorithm
        return $this;
    }

    public static function retrieve($fileName) {
        // Simulate retrieval
        return new Algorithm($fileName);
    }

    public static function clear() {
        // Simulate clearing storage
    }
}

class AlgorithmServiceImpl {
    private $algorithms = [];

    public function listAvailableAlgorithmFiles() {
        return array_keys($this->algorithms);
    }

    public function deleteAlgorithm($algorithm) {
        unset($this->algorithms[$algorithm->name]);
    }

    public function addAlgorithm($algorithm) {
        $this->algorithms[$algorithm->name] = $algorithm;
    }

    public function listAlgorithms($type = null) {
        return array_filter($this->algorithms, function($algo) use ($type) {
            return $type === null || ($type === 'inc' && $algo->isInd);
        });
    }
}

Algorithm::clear();

$service = new AlgorithmServiceImpl();

$algorithm1 = (new Algorithm("someFileName.jar"))->setInd(true)->store();
$service->addAlgorithm($algorithm1);

$algos = $service->listAvailableAlgorithmFiles();
if (count($algos) > 0) {
    $actualAlgorithm = Algorithm::retrieve("someFileName.jar");
    $service->deleteAlgorithm($actualAlgorithm);
}

Algorithm::clear();
--EXPECT--
