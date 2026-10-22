--TEST--
Translated seed (inferredbugs-java: FastDFS_Client/8)
--FILE--
<?php

class TrackerConnectionManager {
    private $trackerList;

    public function setTrackerList(array $trackerList) {
        $this->trackerList = $trackerList;
    }

    public function initTracker() {
        // Initialization logic if necessary
    }

    public function executeFdfsTrackerCmd($command) {
        // Simulate connection failure
        throw new FdfsConnectException("Connection failed");
    }
}

class FdfsConnectException extends Exception {}

class GroupState {}

function main() {
    $ips = ["192.168.174.141:22122", "192.168.1.115:22122"];
    $trackerIpList = $ips;

    $manager = new TrackerConnectionManager();
    $manager->setTrackerList($trackerIpList);
    $manager->initTracker();
    $list = null;

    // First execution
    try {
        $list = $manager->executeFdfsTrackerCmd(new TrackerListGroupsCommand());
    } catch (Exception $e) {
        if (!$e instanceof FdfsConnectException) {
            die("Unexpected exception type: " . get_class($e));
        }
    }

    // Second execution
    try {
        $list = $manager->executeFdfsTrackerCmd(new TrackerListGroupsCommand());
    } catch (Exception $e) {
        if (!$e instanceof FdfsConnectException) {
            die("Unexpected exception type: " . get_class($e));
        }
    }

    echo "Execution result: " . json_encode($list) . "\n";
}

class TrackerListGroupsCommand {}

main();
--EXPECT--
