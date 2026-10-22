--TEST--
Translated seed (inferredbugs-java: azure-iot-sdk-java/20)
--FILE--
<?php

class DeviceClient {
    public function open() {
        // Simulating potential failure
        if (rand(0, 1) === 0) {
            throw new Exception("Failed to open device.");
        }
    }

    public function closeNow() {
        // Simulate closing
    }
}

class DeviceClientManager {
    private static $lock;
    private static $sleepTime = 10;
    private static $connecting = false;
    private $client;

    public function __construct($deviceClient) {
        $this->client = $deviceClient;
    }

    public function connect() {
        while (true) {
            try {
                $this->client->open();
                break;
            } catch (Exception $ex) {
                echo "[connect] - Exception thrown while opening DeviceClient instance: " . $ex->getMessage() . "\n";
            }
            sleep(self::$sleepTime);
        }
    }

    public function reconnect() {
        echo "[reconnect] - Attempting reconnect for device client...\n";
        $lock = self::$lock;

        if (self::$connecting) {
            echo "[reconnect] - DeviceClient is currently connecting, or already connected; skipping...\n";
            return;
        }
        $this->disconnect();
        echo "[reconnect] - DeviceClient Disconnected\n";
        self::$connecting = true;

        $this->connect();
        self::$connecting = false;
    }

    public function disconnect() {
        try {
            echo "[disconnect] - Closing the device client instance...\n";
            $this->client->closeNow();
        } catch (Exception $e) {
            echo "[disconnect] - Exception thrown while closing DeviceClient instance: " . $e->getMessage() . "\n";
        }
    }
}

$deviceClient = new DeviceClient();
$manager = new DeviceClientManager($deviceClient);
$manager->reconnect();
--EXPECT--
