--TEST--
Translated seed (inferredbugs-java: cloud-slang/25)
--FILE--
<?php

class RunEnvironment {
    private $callArguments = [];
    private $returnValues;

    public function putCallArguments(array $arguments) {
        $this->callArguments = $arguments;
    }

    public function removeReturnValues() {
        return $this->returnValues;
    }

    public function setReturnValues($returnValues) {
        $this->returnValues = $returnValues;
    }
}

class ReturnValues {
    private $outputs;

    public function __construct(array $outputs) {
        $this->outputs = $outputs;
    }

    public function getOutputs() {
        return $this->outputs;
    }
}

class ActionSteps {
    public function doAction(RunEnvironment $runEnv, $executionData, $actionType, $className, $methodName, $runtimeServices, $userScript, $nextStepPosition) {
        $callArgs = $runEnv->removeReturnValues();
        $outputs = [];

        if ($nextStepPosition !== null) {
            $runEnv->setReturnValues(new ReturnValues($outputs));
        }

        if ($actionType === 'JAVA' && $methodName === 'doJavaSampleAction') {
            $outputs['name'] = $callArgs['name'] ?? null;
            $outputs['role'] = $callArgs['role'] ?? null;
        } else {
            throw new RuntimeException();
        }

        $runEnv->setReturnValues(new ReturnValues($outputs));
    }
}

$runEnv = new RunEnvironment();
$runEnv->putCallArguments(['name' => 'nameTest', 'role' => 'roleTest']);
$actionSteps = new ActionSteps();

try {
    $actionSteps->doAction($runEnv, [], 'JAVA', 'ContentTestActions', 'doJavaSampleAction', null, null, 2);
    $returnValues = $runEnv->removeReturnValues()->getOutputs();
    var_dump($returnValues);
} catch (RuntimeException $e) {
    echo "An error occurred: " . $e->getMessage();
}
--EXPECT--
