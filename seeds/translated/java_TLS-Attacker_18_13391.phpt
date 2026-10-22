--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/18)
--FILE--
<?php

class ConfigurationException extends Exception {}

class WorkflowTrace {}
class TlsConfig {
    private $workflowInput;
    private $workflowTrace;

    public function setWorkflowInput($workflowInput) {
        $this->workflowInput = $workflowInput;
    }

    public function setWorkflowTrace($workflowTrace) {
        $this->workflowTrace = $workflowTrace;
    }
}

class WorkflowInputDelegate {
    private $workflowInput;

    public function __construct($workflowInput = null) {
        $this->workflowInput = $workflowInput;
    }

    public function applyDelegate($config) {
        $config->setWorkflowInput($this->workflowInput);
        if ($this->workflowInput !== null) {
            $fis = fopen($this->workflowInput, 'r');
            if ($fis === false) {
                throw new ConfigurationException("Could not read WorkflowTrace from " . $this->workflowInput);
            }
            $workflowTrace = new WorkflowTrace(); // Simulate reading the WorkflowTrace
            $config->setWorkflowTrace($workflowTrace);
            fclose($fis);
        }
    }
}

$delegate = new WorkflowInputDelegate("path/to/workflow.xml");
$config = new TlsConfig();
$delegate->applyDelegate($config);

?>
--EXPECT--
