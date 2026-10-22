--TEST--
Translated seed (inferredbugs-java: blynk-server/24)
--FILE--
<?php

class PinType {
    const VIRTUAL = 'VIRTUAL';
    
    public static function getPinType($pinChar) {
        return self::VIRTUAL; // Simplified to return VIRTUAL for this test
    }
}

class Session {
    public function isHardwareConnected($dashId, $deviceId) {
        return true; // Simulate that hardware is connected
    }
    
    public function sendMessageToHardware($dashId, $type, $id, $body, $deviceId) {
        // Simulate sending a message to hardware
    }
}

class User {
    public $profile;
    
    public function __construct() {
        $this->profile = new Profile();
    }
}

class Profile {
    public function getDashById($dashId) {
        return new DashBoard();
    }
}

class DashBoard {
    public $isActive = true;
    public $pinsStorage;

    public function __construct() {
        $this->pinsStorage = [];
    }
    
    public function findWidgetByPin($deviceId, $pin, $pinType) {
        return null; // Simulate no widget found
    }

    public function update($deviceId, $pin, $pinType, $pinValue, $now) {
        // Simulate updating a pin
    }
    
    public function getWidgetByType($type) {
        return new RTC(); // Simulating getting a RTC widget
    }
}

class RTC {
    public function getJsonValue() {
        return json_encode(['rtc' => 'value']); // Simulate RTC JSON value
    }
}

$token = "testToken";
$profile = new Profile();
$user = new User();
$session = new Session();
$deviceId = 1;
$dashId = 1;
$pinString = "V1";
$pinType = PinType::getPinType($pinString[0]);
$pin = (int)$pinString[1];

try {
    if ($profile->getDashById($dashId)->isActive) {
        $now = time();
        $pinValue = "someValue"; // Example pin value
        $profile->getDashById($dashId)->update($deviceId, $pin, $pinType, $pinValue, $now);
        
        $body = json_encode(['pin' => $pin, 'value' => $pinValue]);
        $session->sendMessageToHardware($dashId, 'HARDWARE', 111, $body, $deviceId);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
--EXPECT--
