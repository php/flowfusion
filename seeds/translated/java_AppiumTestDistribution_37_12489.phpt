--TEST--
Translated seed (inferredbugs-java: AppiumTestDistribution/37)
--FILE--
<?php

class AppiumParallelTest {
    private static array $devices = [];
    private static array $deviceMapping = [];
    private string $deviceUdid;

    public function __construct() {
        $this->initializeDevices();
    }

    private function initializeDevices(): void {
        $isMac = str_contains(strtolower(PHP_OS), 'darwin');
        // Simulate adding devices based on OS
        if ($isMac) {
            $this->addDevice("iOS_Device_1");
            $this->addDevice("Android_Device_1");
        } else {
            $this->addDevice("Android_Device_2");
        }
    }

    private function addDevice(string $device): void {
        self::$devices[] = $device;
        self::$deviceMapping[$device] = true;
    }

    public static function getNextAvailableDeviceId(): ?string {
        foreach (self::$deviceMapping as $device => $isAvailable) {
            if ($isAvailable) {
                self::$deviceMapping[$device] = false;
                return $device;
            }
        }
        return null;
    }

    public static function freeDevice(string $deviceId): void {
        self::$deviceMapping[$deviceId] = true;
    }

    public function startAppiumServer(string $device = ''): void {
        $this->deviceUdid = empty($device) ? self::getNextAvailableDeviceId() : $device;

        if ($this->deviceUdid === null) {
            echo "No devices are free to run test or Failed to run test\n";
            return;
        }

        echo "Starting Appium Server for device: $this->deviceUdid\n";
        // Simulate starting server
        sleep(1);
        echo "Appium Server started for $this->deviceUdid\n";
    }

    public function terminateAppiumServer(): void {
        echo "Terminating Appium Server for device: $this->deviceUdid\n";
        self::freeDevice($this->deviceUdid);
        echo "Appium Server terminated for $this->deviceUdid\n";
    }
}

$test = new AppiumParallelTest();
$test->startAppiumServer();
$test->terminateAppiumServer();
--EXPECT--
