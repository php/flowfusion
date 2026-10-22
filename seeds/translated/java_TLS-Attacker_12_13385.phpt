--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/12)
--FILE--
<?php

class Result {
    public $goodTrace = false;
    public $executedTraces = 0;

    public function setGoodTrace($value) {
        $this->goodTrace = $value;
    }

    public function incrementExecutedTraces() {
        $this->executedTraces++;
    }
}

class AnalyzeGoodModificationRule {
    private $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function applies($result) {
        return $result->goodTrace;
    }

    public function onApply($result) {
        $result->incrementExecutedTraces();
    }

    public function getExecutedTraces() {
        return $this->executedTraces;
    }

    public function getConfig() {
        return $this->config;
    }

    public function report() {
        return null; // Placeholder for report
    }
}

$config = "unit_test_config/";
$rule = new AnalyzeGoodModificationRule($config);
$result = new Result();

$result->setGoodTrace(true);
$applies = $rule->applies($result);

$rule->onApply($result);
$executedTraces = $result->executedTraces;
$configValue = $rule->getConfig();

$rule->onApply($result);
$executedTracesAfterSecondApply = $result->executedTraces;

$modificationsCount = $executedTracesAfterSecondApply;

if ($modificationsCount > 0) {
    exit(0);
} else {
    exit(1);
}
--EXPECT--
