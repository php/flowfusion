--TEST--
Translated seed (inferredbugs-java: cloudsim-plus/19)
--FILE--
<?php

class Cloudlet {
    private $utilizationCpu = 0;
    private $utilizationRam = 0;

    public function getUtilizationOfCpu() {
        return $this->utilizationCpu;
    }

    public function setUtilizationOfCpu($utilization) {
        $this->utilizationCpu = $utilization;
    }

    public function getUtilizationOfRam() {
        return $this->utilizationRam;
    }

    public function setUtilizationOfRam($utilization) {
        $this->utilizationRam = $utilization;
    }
}

class TaskUsage {
    public $meanCpuUsageRate;
    public $canonicalMemoryUsage;

    public function __construct($cpuUsage, $memoryUsage) {
        $this->meanCpuUsageRate = $cpuUsage;
        $this->canonicalMemoryUsage = $memoryUsage;
    }
}

class Simulation {
    private $cloudletUsageChangeEvents = [];

    public function addCloudletUsageChangeEvent($event) {
        $this->cloudletUsageChangeEvents[] = $event;
    }

    public function run() {
        foreach ($this->cloudletUsageChangeEvents as $event) {
            $event();
        }
    }
}

$cloudlet = new Cloudlet();
$cloudlet->setUtilizationOfCpu(0.5);
$cloudlet->setUtilizationOfRam(256);

$newTaskUsage = new TaskUsage(0.75, 512);
$simulation = new Simulation();

if ($cloudlet->getUtilizationOfCpu() != $newTaskUsage->meanCpuUsageRate) {
    $cloudlet->setUtilizationOfCpu($newTaskUsage->meanCpuUsageRate);
}

if ($cloudlet->getUtilizationOfRam() != $newTaskUsage->canonicalMemoryUsage) {
    $cloudlet->setUtilizationOfRam($newTaskUsage->canonicalMemoryUsage);
}

$simulation->addCloudletUsageChangeEvent(function() use ($cloudlet) {
    echo "CPU Utilization: " . $cloudlet->getUtilizationOfCpu() . "\n";
    echo "RAM Utilization: " . $cloudlet->getUtilizationOfRam() . "\n";
});

$simulation->run();
--EXPECT--
