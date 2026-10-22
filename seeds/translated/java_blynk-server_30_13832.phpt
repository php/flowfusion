--TEST--
Translated seed (inferredbugs-java: blynk-server/30)
--FILE--
<?php

class OTAManager {
    public function buildOTAInitCommandBody($otaFile) {
        return "OTA_INIT_COMMAND_FOR_" . $otaFile;
    }

    public function stop($initiator) {
        // Stub for stopping OTA
    }
}

class TokenValue {
    public $user;
    public $deviceId;

    public function __construct($user, $deviceId) {
        $this->user = $user;
        $this->deviceId = $deviceId;
    }
}

class User {
    public $email;

    public function __construct($email) {
        $this->email = $email;
    }
}

class Session {
    public function sendMessageToHardware($command, $port, $body) {
        return false;  // Simulating no hardware error
    }
}

class UserKey {
    public function __construct($user) {
    }
}

class Holder {
    public $otaManager;

    public function __construct() {
        $this->otaManager = new OTAManager();
    }
}

function getTokenValueByToken($token) {
    if ($token === "valid_token") {
        return new TokenValue(new User("user@example.com"), 1);
    }
    return null;
}

function getSession($user) {
    return new Session();
}

$otaManager = new OTAManager();
$token = "valid_token";
$filename = null;

$tokenValue = getTokenValueByToken($token);
if ($tokenValue === null) {
    exit("Invalid token.");
}

$user = $tokenValue->user;
$deviceId = $tokenValue->deviceId;

if ($user === null) {
    exit("Invalid auth credentials.");
}

$session = getSession($user);
if ($session === null) {
    exit("Device wasn't connected yet.");
}

$otaFile = "/static/ota/" . ($filename === null ? "firmware_ota.bin" : $filename);
$body = $otaManager->buildOTAInitCommandBody($otaFile);
if ($session->sendMessageToHardware(null, 7777, $body)) {
    exit("No device in session.");
}

exit("OTA process started successfully.");
--EXPECT--
