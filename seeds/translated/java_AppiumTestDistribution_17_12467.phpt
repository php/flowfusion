--TEST--
Translated seed (inferredbugs-java: AppiumTestDistribution/17)
--FILE--
<?php

class AppiumParallelTest {
    private $device_udid = null;
    private static $deviceMapping = [];
    private static $devices = [];

    public static function init() {
        if (stripos(PHP_OS, 'Darwin') !== false) {
            // Simulating device addition for macOS
            self::$devices = ['iOS_Device_1', 'Android_Device_1'];
        } else {
            // Simulating device addition for other OS
            self::$devices = ['Android_Device_2'];
        }
        foreach (self::$devices as $device) {
            self::$deviceMapping[$device] = true;
        }
    }

    public static function getNextAvailableDeviceId() {
        foreach (self::$deviceMapping as $device => $isAvailable) {
            if ($isAvailable) {
                self::$deviceMapping[$device] = false;
                return $device;
            }
        }
        return null;
    }

    public static function freeDevice($deviceId) {
        self::$deviceMapping[$deviceId] = true;
    }

    public function startAppiumServer() {
        $this->device_udid = self::getNextAvailableDeviceId();
        if ($this->device_udid === null) {
            echo "No devices are free to run test or Failed to run test\n";
            return;
        }
        echo "Starting Appium server on device: {$this->device_udid}\n";
        // Simulating Appium server startup logic here
    }

    public function killAppiumServer() {
        echo "Closing Appium session for device: {$this->device_udid}\n";
        self::freeDevice($this->device_udid);
    }
}

AppiumParallelTest::init();
$test = new AppiumParallelTest();
$test->startAppiumServer();
$test->killAppiumServer();
--EXPECT--
