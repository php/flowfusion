--TEST--
Translated seed (inferredbugs-java: cloud-slang/26)
--FILE--
<?php

class ExecutionPlan {
    private $name;
    private $language;
    private $flowUuid;
    private $steps = [];

    public function setName($name) { $this->name = $name; }
    public function setLanguage($language) { $this->language = $language; }
    public function setFlowUuid($flowUuid) { $this->flowUuid = $flowUuid; }
    public function addStep($step) { $this->steps[] = $step; }
}

class StepFactory {
    public function createStartStep($id, $preExecActionData, $inputs, $name) {
        return "Start Step: $name with ID $id";
    }
    public function createEndStep($id, $postExecActionData, $outputs, $results, $name, $type) {
        return "End Step: $name with ID $id";
    }
    public function createActionStep($id, $actionData) {
        return "Action Step with ID $id";
    }
}

function createExecutionPlan($name, $language, $uuid) {
    $executionPlan = new ExecutionPlan();
    $executionPlan->setName($name);
    $executionPlan->setLanguage($language);
    $executionPlan->setFlowUuid($uuid);
    
    $stepFactory = new StepFactory();
    $executionPlan->addStep($stepFactory->createStartStep(1, null, [], $name));
    $executionPlan->addStep($stepFactory->createActionStep(2, null));
    $executionPlan->addStep($stepFactory->createEndStep(3, null, [], [], $name, 'OPERATION'));
    
    return $executionPlan;
}

$executionPlan = createExecutionPlan("Test Operation", "CloudSlang", "uuid-123");
--EXPECT--
