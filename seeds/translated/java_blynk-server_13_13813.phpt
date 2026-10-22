--TEST--
Translated seed (inferredbugs-java: blynk-server/13)
--FILE--
<?php

class User {
    public $dashId;

    public function getDashIdByToken($token) {
        return $this->dashId;
    }
}

class DashBoard {
    public $isActive;
    public $storagePins;

    public function getWidgetByType($type) {
        return new Notification();
    }

    public function findWidgetByPin($pin, $pinType) {
        return null;
    }
}

class Session {
    public function isHardwareConnected($dashId) {
        return true;
    }

    public function sendToApps($command, $value, $data) {
    }
}

class TokenManager {
    public function getUserByToken($token) {
        return new User();
    }
}

class Notification {
    public static function isWrongBody($body) {
        return empty($body);
    }

    public function push($wrapper, $body, $count) {
    }
}

class Response {
    public static function ok($data = null, $contentType = null) {
        echo "OK: ", $data, PHP_EOL;
    }

    public static function badRequest($message) {
        echo "Bad Request: ", $message, PHP_EOL;
    }
}

function updateWidgetPinData($token, $pinString, $pinValues) {
    $tokenManager = new TokenManager();
    $user = $tokenManager->getUserByToken($token);
    if ($user === null) {
        return Response::badRequest("Invalid token.");
    }
    
    $dashId = $user->getDashIdByToken($token);
    if ($dashId === null) {
        return Response::badRequest("Didn't find dash id for token.");
    }

    $dash = new DashBoard();
    $dash->isActive = true;

    if (!$dash->isActive) {
        return Response::badRequest("Project is not active.");
    }

    $pinValue = implode(',', $pinValues);
    // Simulating saving pin data
    $dash->storagePins[$pinString] = $pinValue;

    $session = new Session();
    if ($session === null) {
        return Response::ok();
    }

    $session->sendToApps('HARDWARE', 111, $dashId . ',' . $pinValue);
    return Response::ok();
}

$token = "exampleToken";
$pinString = "V1";
$pinValues = ["value1", "value2"];
updateWidgetPinData($token, $pinString, $pinValues);
--EXPECT--
