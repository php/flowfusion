--TEST--
Translated seed (inferredbugs-java: AppiumTestDistribution/36)
--FILE--
<?php

class AppiumParallelTest {
    private static $devices = [];
    private static $deviceMapping = [];
    private $device_udid;

    public function __construct() {
        $this->initializeDevices();
    }

    private function initializeDevices() {
        if (stripos(PHP_OS, 'Darwin') !== false) {
            self::$devices = ["iOSDevice1", "iOSDevice2", "AndroidDevice1"];
        } else {
            self::$devices = ["AndroidDevice1", "AndroidDevice2"];
        }
        foreach (self::$devices as $device) {
            self::$deviceMapping[$device] = true;
        }
    }

    public static function getNextAvailableDeviceId() {
        foreach (array_keys(self::$deviceMapping) as $device) {
            if (self::$deviceMapping[$device]) {
                self::$deviceMapping[$device] = false; // mark as in use
                return $device;
            }
        }
        return null;
    }

    public static function freeDevice($deviceId) {
        self::$deviceMapping[$deviceId] = true; // mark as free
    }

    public function startAppiumServer($device = "") {
        if ($device === "") {
            $this->device_udid = self::getNextAvailableDeviceId();
        } else {
            $this->device_udid = $device;
        }

        if ($this->device_udid === null) {
            echo "No devices are free to run test or Failed to run test\n";
            return;
        }
        echo "Starting server on device: " . $this->device_udid . "\n";
        // Simulate server starting
        sleep(1);
    }

    public function captureScreenshot() {
        echo "Capturing screenshot on device: " . $this->device_udid . "\n";
    }

    public function endTest() {
        self::freeDevice($this->device_udid);
    }
}

function main() {
    $appiumTest = new AppiumParallelTest();
    $appiumTest->startAppiumServer();
    $appiumTest->captureScreenshot();
    $appiumTest->endTest();
}

main();
--EXPECT--
