--TEST--
Translated seed (inferredbugs-java: AppiumTestDistribution/29)
--FILE--
<?php

class DeviceManager {
    private $devices = [];
    private $deviceMapping = [];
    private $deviceIndex = 0;

    public function addDevice($device) {
        $this->devices[] = $device;
        $this->deviceMapping[$device] = true;
    }

    public function getNextAvailableDevice() {
        foreach ($this->devices as $device) {
            if ($this->deviceMapping[$device]) {
                $this->deviceMapping[$device] = false;
                return $device;
            }
        }
        return null;
    }

    public function freeDevice($device) {
        $this->deviceMapping[$device] = true;
    }
}

function simulateTestExecution($deviceManager) {
    $device = $deviceManager->getNextAvailableDevice();
    if ($device === null) {
        echo "No devices are available to run the test.\n";
        return;
    }
    echo "Running test on device: $device\n";

    // Simulate a test execution
    sleep(2); // Simulating a test that takes some time to run

    // Freeing the device after test execution
    $deviceManager->freeDevice($device);
    echo "Test completed on device: $device\n";
}

$deviceManager = new DeviceManager();
$deviceManager->addDevice('Device_1');
$deviceManager->addDevice('Device_2');
$deviceManager->addDevice('Device_3');

// Simulate running tests in parallel
for ($i = 0; $i < 5; $i++) {
    simulateTestExecution($deviceManager);
}

?>
--EXPECT--
