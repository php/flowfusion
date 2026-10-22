--TEST--
Translated seed (inferredbugs-java: AppiumTestDistribution/10)
--FILE--
<?php

class ConcurrentDeviceManager {
    private $deviceMapping = [];
    
    public function __construct(array $devices) {
        foreach ($devices as $device) {
            $this->deviceMapping[$device] = true;
        }
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
        $this->deviceMapping[$deviceId] = true;
    }
}

function main() {
    $devices = ['device1', 'device2', 'device3'];
    $deviceManager = new ConcurrentDeviceManager($devices);
    
    $deviceId = $deviceManager->getNextAvailableDeviceId();
    if ($deviceId === null) {
        echo "No devices are free to run test or Failed to run test\n";
        return;
    }

    // Simulate device usage
    echo "Using device: $deviceId\n";
    
    // Free the device when done
    $deviceManager->freeDevice($deviceId);
    echo "Device $deviceId is now free\n";
}

main();
--EXPECT--
