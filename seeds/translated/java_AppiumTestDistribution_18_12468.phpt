--TEST--
Translated seed (inferredbugs-java: AppiumTestDistribution/18)
--FILE--
<?php

class AppiumParallelTest {
    public $device_udid;
    public $deviceMapping = [];
    public static $devices = [];
    
    public function __construct() {
        $this->initializeDevices();
    }

    private function initializeDevices() {
        $this->deviceMapping = [
            "device1" => true,
            "device2" => true,
            "device3" => true,
        ];
        self::$devices = array_keys($this->deviceMapping);
    }

    public function getNextAvailableDeviceId() {
        foreach ($this->deviceMapping as $device => $available) {
            if ($available) {
                $this->deviceMapping[$device] = false;
                return $device;
            }
        }
        return null;
    }

    public function freeDevice($deviceId) {
        if (array_key_exists($deviceId, $this->deviceMapping)) {
            $this->deviceMapping[$deviceId] = true;
        }
    }

    public function startAppiumServer() {
        $this->device_udid = $this->getNextAvailableDeviceId();
        if ($this->device_udid === null) {
            echo "No devices are free to run test\n";
            return;
        }

        // Simulating Appium server start based on device
        echo "Starting Appium server for device: " . $this->device_udid . "\n";
        
        // Simulate some behavior that stresses the same runtime subsystem
        // Here, we'll simulate an "integer overflow" with a large calculation
        $this->simulateIntegerOverflow();
    }

    public function simulateIntegerOverflow() {
        $bigNumber = PHP_INT_MAX;
        $result = $bigNumber + 1;
        echo "Result of integer overflow simulation: " . $result . "\n"; // In PHP, this will wrap around
    }

    public function killAppiumServer() {
        echo "Killing Appium server for device: " . $this->device_udid . "\n";
        $this->freeDevice($this->device_udid);
    }
}

$test = new AppiumParallelTest();
$test->startAppiumServer();
$test->killAppiumServer();
--EXPECT--
