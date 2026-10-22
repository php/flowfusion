--TEST--
Translated seed (inferredbugs-java: acs-aem-commons/11)
--FILE--
<?php

class SyntheticWorkflowRunnerImpl {
    private $processes = [];

    public function bindWorkflowProcesses($process, $map) {
        $this->processes[$map['process.label']] = $process;
    }

    public function start($param1, $path, $labels, $metadata) {
        foreach ($labels as $label) {
            if (isset($this->processes[$label])) {
                $this->processes[$label]->execute($path, $metadata);
            }
        }
    }
}

class WFDataWorkflowProcess {
    public function execute($path, $metadata) {
        // Simulate workflow execution
    }
}

class SetDataWorkflowProcess {
    public function execute($path, $metadata) {
        // Simulate setting data
    }
}

class ReadDataWorkflowProcess {
    public function execute($path, $metadata) {
        // Simulate reading data
    }
}

$swr = new SyntheticWorkflowRunnerImpl();

$map1 = ["process.label" => "test"];
$swr->bindWorkflowProcesses(new WFDataWorkflowProcess(), $map1);

$swr->start(null, "/content/test", ["test"], []);

$map2 = ["process.label" => "set"];
$swr->bindWorkflowProcesses(new SetDataWorkflowProcess(), $map2);

$map3 = ["process.label" => "read"];
$swr->bindWorkflowProcesses(new ReadDataWorkflowProcess(), $map3);

$swr->start(null, "/content/test", ["set", "read"], []);
--EXPECT--
