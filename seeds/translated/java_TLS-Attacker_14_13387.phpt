--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/14)
--FILE--
<?php

class Result {
    public $success;
    public $timeStart;
    public $timeEnd;
    
    public function __construct($success, $timeStart, $timeEnd) {
        $this->success = $success;
        $this->timeStart = $timeStart;
        $this->timeEnd = $timeEnd;
    }
}

class AnalyzeModificationRule {
    private $executedTraces = 0;

    public function applies($result) {
        return !$result->success;
    }
    
    public function onApply($result) {
        $this->executedTraces++;
    }

    public function getExecutedTraces() {
        return $this->executedTraces;
    }
}

class TestVector {
    private $modifications = [];

    public function addModification($modification) {
        $this->modifications[] = $modification;
    }
}

class Modification {}

class AddMessageModification extends Modification {}
class AddRecordModification extends Modification {}
class ChangeServerCertificateModification extends Modification {}
class DuplicateMessageModification extends Modification {}
class ModifyFieldModification extends Modification {}

$rule = new AnalyzeModificationRule();
$vector = new TestVector();

$vector->addModification(new AddMessageModification());
$vector->addModification(new AddRecordModification());
$vector->addModification(new ChangeServerCertificateModification());
$vector->addModification(new DuplicateMessageModification());
$vector->addModification(new ModifyFieldModification());

$result = new Result(false, 1000, 2000);
if ($rule->applies($result)) {
    $rule->onApply($result);
}

$result2 = new Result(false, 1000, 2000);
$rule->onApply($result2);

$traceCount = $rule->getExecutedTraces();
$rule->onApply($result);
$traceCount += $rule->getExecutedTraces();

$vector->addModification(new AddMessageModification());
$rule->onApply($result);
$traceCount += $rule->getExecutedTraces();

exit(0);
--EXPECT--
