--TEST--
Translated seed (inferredbugs-java: blynk-server/28)
--FILE--
<?php

class TokenManager {
    public function getTokenValueByToken($token) {
        // Mock implementation
        return $token === "valid_token" ? (object)["user" => (object)["profile" => new UserProfile()], "dashId" => 1, "deviceId" => 1] : null;
    }
}

class UserProfile {
    public function getDashById($id) {
        return new DashBoard();
    }
}

class DashBoard {
    public $isActive = true;

    public function findWidgetByPin($deviceId, $pin, $pinType) {
        return null; // Simulate no widget found
    }

    public function update($deviceId, $pin, $pinType, $pinValue, $now) {
        // Mock update
    }

    public function getJsonValue() {
        return json_encode(["status" => "success"]);
    }
}

class Response {
    public static function badRequest($message) {
        echo json_encode(["status" => "error", "message" => $message]);
    }

    public static function ok($data = null) {
        echo json_encode(["status" => "success", "data" => $data]);
    }
}

$token = "valid_token";
$tokenManager = new TokenManager();
$tokenValue = $tokenManager->getTokenValueByToken($token);

if ($tokenValue === null) {
    Response::badRequest("Invalid token.");
    exit;
}

$user = $tokenValue->user;
$dashId = $tokenValue->dashId;
$deviceId = $tokenValue->deviceId;

$pinString = "A1"; // Simulated pin input
$pinType = $pinString[0]; // Example pin type extraction
$pin = (int)$pinString[1]; // Example pin extraction

$dashBoard = $user->profile->getDashById($dashId);
$widget = $dashBoard->findWidgetByPin($deviceId, $pin, $pinType);

if ($widget === null) {
    Response::badRequest("Requested pin doesn't exist in the app.");
    exit;
}

$pinValue = "123"; // Simulated pin value
$now = time();
$dashBoard->update($deviceId, $pin, $pinType, $pinValue, $now);
Response::ok($dashBoard->getJsonValue());
--EXPECT--
