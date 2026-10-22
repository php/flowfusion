--TEST--
Translated seed (inferredbugs-java: cloudsim-plus/2)
--FILE--
<?php

class Host {
    private $peList;
    private $vmList;

    public function __construct($pes) {
        $this->peList = array_fill(0, $pes, true);
        $this->vmList = [];
    }

    public function getPeList() {
        return $this->peList;
    }
    
    public function getVmList() {
        return $this->vmList;
    }

    public function addVm($vm) {
        $this->vmList[] = $vm;
    }

    public function setPeStatus($index, $status) {
        $this->peList[$index] = $status;
    }

    public function getNumberOfWorkingPes() {
        return count(array_filter($this->peList));
    }

    public function getNumberOfPes() {
        return count($this->peList);
    }
}

class Vm {
    public $failed = false;

    public function isFailed() {
        return $this->failed;
    }

    public function setFailed($status) {
        $this->failed = $status;
    }
}

class HostFaultInjection {
    private $host;
    private $numberOfFailedPesRandom;

    public function __construct($host) {
        $this->host = $host;
        $this->numberOfFailedPesRandom = mt_rand(0, $this->host->getNumberOfPes());
    }

    public function generateFailure() {
        $numberOfFailedPes = $this->numberOfFailedPesRandom;
        for ($i = 0; $i < $numberOfFailedPes; $i++) {
            $this->host->setPeStatus($i, false);
        }
        $this->processFailure();
    }

    private function processFailure() {
        $workingPes = $this->host->getNumberOfWorkingPes();
        $totalPes = $this->host->getNumberOfPes();
        
        if ($workingPes === 0) {
            foreach ($this->host->getVmList() as $vm) {
                $vm->setFailed(true);
            }
        }
    }
}

$host = new Host(4);
$vm1 = new Vm();
$vm2 = new Vm();
$host->addVm($vm1);
$host->addVm($vm2);

$faultInjection = new HostFaultInjection($host);
$faultInjection->generateFailure();
--EXPECT--
