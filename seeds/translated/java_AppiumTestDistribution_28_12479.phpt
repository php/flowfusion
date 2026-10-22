--TEST--
Translated seed (inferredbugs-java: AppiumTestDistribution/28)
--FILE--
<?php

class AppiumParallelTest {
    public static $devices = [];
    public static $deviceMapping = [];

    public function __construct() {
        $this->initializeDevices();
    }

    public function initializeDevices() {
        $os = strtolower(PHP_OS);
        if (strpos($os, 'darwin') !== false) { // macOS
            self::$devices = ['iOSDevice1', 'iOSDevice2']; // Example devices
        } else { // Non-macOS
            self::$devices = ['AndroidDevice1', 'AndroidDevice2']; // Example devices
        }
        foreach (self::$devices as $device) {
            self::$deviceMapping[$device] = true;
        }
        print_r(self::$deviceMapping);
    }

    public static function getNextAvailableDeviceId() {
        foreach (self::$deviceMapping as $device => $isFree) {
            if ($isFree) {
                self::$deviceMapping[$device] = false;
                return $device;
            }
        }
        return null;
    }

    public static function freeDevice($deviceId) {
        self::$deviceMapping[$deviceId] = true;
    }

    public function runTest() {
        $deviceId = self::getNextAvailableDeviceId();
        if ($deviceId === null) {
            echo "No devices are free to run test or Failed to run test\n";
            return;
        }

        echo "Running tests on device: $deviceId\n";
        // Simulate some test execution
        sleep(2); // Simulating test delay
        echo "Test completed on device: $deviceId\n";

        // Free the device after the test
        self::freeDevice($deviceId);
    }
}

$test = new AppiumParallelTest();
$test->runTest();
$test->runTest(); // Run another test to see device allocation
$test->runTest(); // Continue running tests
--EXPECT--
