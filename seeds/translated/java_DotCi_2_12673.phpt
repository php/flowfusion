--TEST--
Translated seed (inferredbugs-java: DotCi/2)
--FILE--
<?php

class DbBackedBuild {
    private $state;
    private $timestamp;
    private $projectId;

    public function __construct($project) {
        $this->projectId = $project->getId();
        $this->timestamp = time();
    }

    public function saveState() {
        $dbObj = [];
        $dbObj['state'] = $this->state;
        $dbObj['timestamp'] = $this->timestamp;
        // Simulate database save
        $this->simulateDbSave($dbObj);
    }

    public function restoreState($dbObj) {
        $this->state = $dbObj['state'];
        if (isset($dbObj['timestamp'])) {
            $this->timestamp = $dbObj['timestamp'];
        }
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function getState() {
        return $this->state;
    }

    private function simulateDbSave($dbObj) {
        // Simulate a database save operation
    }

    public function getId() {
        return $this->projectId . '_' . $this->timestamp;
    }
}

class Project {
    private $id;

    public function __construct($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }
}

function main() {
    $project = new Project(123);
    $build = new DbBackedBuild($project);
    
    $build->setState("IN_PROGRESS");
    $build->saveState();
    
    $dbObj = ['state' => 'COMPLETED', 'timestamp' => time()];
    $build->restoreState($dbObj);
    
    echo "Build ID: " . $build->getId() . "\n";
    echo "Current State: " . $build->getState() . "\n";
}

main();
--EXPECT--
