--TEST--
Translated seed (inferredbugs-java: cloudsim-plus/4)
--FILE--
<?php
class CloudSim {
    private $time = 0;

    public function start() {
        $this->time = 0;
        while ($this->time < 10) {
            $this->time++;
            $this->update();
        }
    }

    public function getTime() {
        return $this->time;
    }
}

class Host {
    private $cpuUtilization = 0;

    public function updateCpuUtilization() {
        $this->cpuUtilization += 0.1;
    }

    public function getCpuUtilization() {
        return $this->cpuUtilization;
    }

    public function destroyVm($vm) {
        echo "\n# " . (new CloudSim())->getTime() . ": Destroying VM due to CPU overload. Current Host CPU usage is " . $this->getCpuUtilization() * 100 . "%\n";
    }
}

class Vm {
    private $cpuUtilization = 0;

    public function updateCpuUtilization() {
        $this->cpuUtilization += 0.1;
    }

    public function getCpuUtilization() {
        return $this->cpuUtilization;
    }

    public function isCreated() {
        return true; // Simplified for this example
    }
}

$cloudSim = new CloudSim();
$host = new Host();
$vm = new Vm();

for ($i = 0; $i < 10; $i++) {
    $cloudSim->start();
    $vm->updateCpuUtilization();
    $host->updateCpuUtilization();

    if ($vm->getCpuUtilization() > 0.9 && $vm->isCreated()) {
        $host->destroyVm($vm);
    }

    echo "# " . $cloudSim->getTime() . ": Host CPU Utilization " . $host->getCpuUtilization() * 100 . "%\n";
}
--EXPECT--
