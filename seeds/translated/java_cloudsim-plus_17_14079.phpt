--TEST--
Translated seed (inferredbugs-java: cloudsim-plus/17)
--FILE--
<?php

class MachineEvent {
    public $cpuCores;
    public $ram;
    public $timestamp;
    public $machineId;

    public function setCpuCores($cpuCores) {
        $this->cpuCores = $cpuCores;
        return $this;
    }

    public function setRam($ram) {
        $this->ram = $ram;
        return $this;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function setMachineId($machineId) {
        $this->machineId = $machineId;
        return $this;
    }
}

class GoogleMachineEventsTraceReader {
    private $maxRamCapacity = 16384; // 16GB in MB
    private $maxCpuCores = 8;
    private $laterAvailableHosts = [];
    private $hostsForRemoval = [];

    public function processLine($line) {
        $data = explode(',', $line);
        $event = (new MachineEvent())
            ->setCpuCores(round((float)$data[4] * $this->maxCpuCores))
            ->setRam(round((float)$data[5] * $this->maxRamCapacity))
            ->setTimestamp((float)$data[0])
            ->setMachineId((int)$data[1]);
        if ($data[0] == 0 && (int)$data[2] === 1) {
            $this->laterAvailableHosts[] = $event;
        } else if ($data[0] > 0) {
            $this->hostsForRemoval[] = $event;
        }
    }
}

$traceReader = new GoogleMachineEventsTraceReader();
$lines = [
    "0,1,1,0,0.5,0.5",
    "1,2,1,0,0.75,0.75",
    "0,3,1,0,0.25,0.25"
];
foreach ($lines as $line) {
    $traceReader->processLine($line);
}
--EXPECT--
