--TEST--
Translated seed (inferredbugs-java: Sundial/1)
--FILE--
<?php

class JobExecutionContext {
    private $trigger;

    public function __construct($trigger) {
        $this->trigger = $trigger;
    }

    public function getTrigger() {
        return $this->trigger;
    }
}

class Trigger {
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
}

class ExecutingJobsManager {
    private $executingJobs = [];
    private $numJobsFired = 0;

    public function jobToBeExecuted(JobExecutionContext $context) {
        $this->numJobsFired++;
        $this->executingJobs[$context->getTrigger()->getName()] = $context;
    }

    public function jobWasExecuted(JobExecutionContext $context) {
        unset($this->executingJobs[$context->getTrigger()->getName()]);
    }

    public function getNumJobsCurrentlyExecuting() {
        return count($this->executingJobs);
    }
}

$jobMgr = new ExecutingJobsManager();
$trigger1 = new Trigger("trigger1");
$trigger2 = new Trigger("trigger2");

$jobMgr->jobToBeExecuted(new JobExecutionContext($trigger1));
$jobMgr->jobToBeExecuted(new JobExecutionContext($trigger2));

$jobMgr->jobWasExecuted(new JobExecutionContext($trigger1));

echo $jobMgr->getNumJobsCurrentlyExecuting();
--EXPECT--
