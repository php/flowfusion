--TEST--
Translated seed (inferredbugs-java: FastDFS_Client/7)
--FILE--
<?php

class FdfsConnectException extends Exception {}

class TrackerListGroupsCommand {}

class TrackerConnectionManager {
    private $trackerList;

    public function setTrackerList(array $trackerList) {
        $this->trackerList = $trackerList;
    }

    public function initTracker() {
        // Simulating initialization
    }

    public function executeFdfsTrackerCmd($command) {
        // Simulating connection failure
        throw new FdfsConnectException("Connection failed");
    }
}

function createPool() {
    // Simulating a connection pool creation
}

$ips = ["192.168.174.141:22122", "192.168.1.115:22122"];
$trackerIpList = $ips;

// Initialize
$manager = new TrackerConnectionManager();
$manager->setTrackerList($trackerIpList);
$manager->initTracker();
$list = null;

// First execution
try {
    // Connection fails
    $list = $manager->executeFdfsTrackerCmd(new TrackerListGroupsCommand());
} catch (Exception $e) {
    if (!($e instanceof FdfsConnectException)) {
        throw $e; // Rethrow wrong exception types
    }
}

// Second execution
try {
    // Connection fails
    $list = $manager->executeFdfsTrackerCmd(new TrackerListGroupsCommand());
} catch (Exception $e) {
    if (!($e instanceof FdfsConnectException)) {
        throw $e; // Rethrow wrong exception types
    }
} 

echo "Execution completed without errors.";
--EXPECT--
