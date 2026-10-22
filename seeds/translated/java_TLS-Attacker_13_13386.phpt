--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/13)
--FILE--
<?php

class TestVector {
    private $modifications = [];
    
    public function addModification($modification) {
        $this->modifications[] = $modification;
    }
}

class Result {
    public function __construct($a, $b, $c, $d, $e, $f, $g) {}
}

class AnalyzeModificationRule {
    private $config;
    private $executedTraces = 0;

    public function __construct($config) {
        $this->config = $config;
    }

    public function applies($result) {
        return true;
    }

    public function onApply($result) {
        $this->executedTraces++;
    }

    public function getExecutedTraces() {
        return $this->executedTraces;
    }
}

class Modification {}

class AddMessageModification extends Modification {}
class AddRecordModification extends Modification {}
class ChangeServerCertificateModification extends Modification {}
class DuplicateMessageModification extends Modification {}
class ModifyFieldModification extends Modification {}

$config = [];
$rule = new AnalyzeModificationRule($config);
$vector = new TestVector();
$vector->addModification(new AddMessageModification());
$vector->addModification(new AddRecordModification());
$vector->addModification(new ChangeServerCertificateModification());
$vector->addModification(new DuplicateMessageModification());
$vector->addModification(new ModifyFieldModification());

$result = new Result(false, false, 1000, 2000, null, $vector, "unittest.id");
$rule->onApply($result);
$rule->onApply($result);
$executedTraces = $rule->getExecutedTraces();
--EXPECT--
