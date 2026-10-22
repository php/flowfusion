--TEST--
Translated seed (inferredbugs-java: cloudsim-plus/21)
--FILE--
<?php

class Host {
    private static $defaultRamCapacity = 10240; // 10GB in MB
    private static $defaultBwCapacity = 1000; // Mbps
    private static $defaultStorageCapacity = 512000; // 500GB in MB
    private $ram;
    private $bw;
    private $storage;
    private $vmList = [];
    private $active = true;
    private $failed = false;
    
    public function __construct($ram = null, $bw = null, $storage = null) {
        $this->ram = $ram ?: self::$defaultRamCapacity;
        $this->bw = $bw ?: self::$defaultBwCapacity;
        $this->storage = $storage ?: self::$defaultStorageCapacity;
    }

    public function createVm($vm) {
        if ($this->canAllocate($vm)) {
            $this->vmList[] = $vm;
            return true;
        }
        return false;
    }

    public function destroyVm($vm) {
        $key = array_search($vm, $this->vmList);
        if ($key !== false) {
            unset($this->vmList[$key]);
        }
    }

    public function canAllocate($vm) {
        return $vm['ram'] <= $this->ram && $vm['bw'] <= $this->bw && $vm['storage'] <= $this->storage;
    }

    public function setFailed($failed) {
        $this->failed = $failed;
        if ($failed) {
            $this->active = false;
        }
    }

    public function isActive() {
        return $this->active && !$this->failed;
    }
}

$host = new Host();
$vm1 = ['ram' => 2048, 'bw' => 500, 'storage' => 10000];
$vm2 = ['ram' => 4096, 'bw' => 1200, 'storage' => 20000]; // This should fail

$host->createVm($vm1); // should succeed
$host->createVm($vm2); // should fail
--EXPECT--
