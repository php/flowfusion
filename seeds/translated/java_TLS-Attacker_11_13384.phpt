--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/11)
--FILE--
<?php

class AnalyzeGoodModificationRule {
    private $executedTraces = 0;
    private $config;

    public function __construct() {
        $this->config = new stdClass();
    }

    public function applies($result) {
        return $result->isGoodTrace();
    }

    public function onApply($result) {
        $this->executedTraces++;
    }

    public function onDecline($result) {}

    public function report() {
        return $this->executedTraces > 0 ? new stdClass() : null;
    }

    public function getConfig() {
        return $this->config;
    }

    public function getExecutedTraces() {
        return $this->executedTraces;
    }

    public function getTypeMap() {
        return ['ADD_RECORD' => $this->executedTraces, 'ADD_MESSAGE' => $this->executedTraces + 1];
    }
}

class TestVector {
    private $modifications = [];

    public function addModification($modification) {
        $this->modifications[] = $modification;
    }
}

class Result {
    private $goodTrace = false;

    public function isGoodTrace() {
        return $this->goodTrace;
    }

    public function setGoodTrace($good) {
        $this->goodTrace = $good;
    }
}

$config = new stdClass();
$rule = new AnalyzeGoodModificationRule();
$vector = new TestVector();
$result = new Result();

$result->setGoodTrace(false);
var_dump($rule->applies($result));
$result->setGoodTrace(true);
var_dump($rule->applies($result));

$rule->onApply($result);
var_dump($rule->getExecutedTraces());

$rule->onApply($result);
var_dump($rule->getExecutedTraces());

$vector->addModification('AddMessageModification');
$rule->onApply($result);
$map = $rule->getTypeMap();
var_dump($map['ADD_RECORD'], $map['ADD_MESSAGE']);

$config = null;
--EXPECT--
