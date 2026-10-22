--TEST--
Translated seed (inferredbugs-java: Metanome/17)
--FILE--
<?php

class Algorithm {
    private $fileName;
    private $isInd = false;
    private static $storage = [];

    public function __construct($fileName) {
        $this->fileName = $fileName;
    }

    public function setInd($value) {
        $this->isInd = $value;
        return $this;
    }

    public function store() {
        self::$storage[$this->fileName] = $this;
    }

    public static function retrieve($fileName) {
        return self::$storage[$fileName] ?? null;
    }

    public static function clear() {
        self::$storage = [];
    }
}

class AlgorithmServiceImpl {
    public function listAvailableAlgorithmFiles() {
        return array_keys(Algorithm::$storage);
    }

    public function deleteAlgorithm($algorithm) {
        unset(Algorithm::$storage[$algorithm->fileName]);
    }

    public function listAlgorithms() {
        return array_values(Algorithm::$storage);
    }

    public function addAlgorithm($algorithm) {
        $algorithm->store();
    }

    public function listInclusionDependencyAlgorithms() {
        return array_filter(Algorithm::$storage, function($algo) {
            return $algo->isInd;
        });
    }
}

$service = new AlgorithmServiceImpl();
Algorithm::clear();

$algorithm1 = (new Algorithm("someFileName.jar"))->setInd(true);
$algorithm1->store();

$algos = $service->listAvailableAlgorithmFiles();

$actualAlgorithm = Algorithm::retrieve("someFileName.jar");
$service->deleteAlgorithm($actualAlgorithm);
$retrievedAlgorithm = Algorithm::retrieve("someFileName.jar");
Algorithm::clear();
--EXPECT--
