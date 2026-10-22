--TEST--
Translated seed (inferredbugs-java: blynk-server/27)
--FILE--
<?php

function getPinType($char) {
    return $char === 'V' ? 'VIRTUAL' : 'PHYSICAL'; // Simplified conversion
}

$token = "someToken";
$tokenValue = null; // Simulate getting token value

if ($tokenValue === null) {
    echo "Invalid token.";
    exit;
}

$user = $tokenValue->user;
$dashId = $tokenValue->dashId;
$deviceId = $tokenValue->deviceId;

$dashBoard = $user->profile->getDashById($dashId);

// Simulate pin string processing
$pinString = "V1";
$pinType = getPinType($pinString[0]);
$pin = (int) substr($pinString, 1);

$widget = $dashBoard->findWidgetByPin($deviceId, $pin, $pinType);

if ($widget === null) {
    $value = $dashBoard->pinsStorage->get("{$deviceId}_{$pinType}_{$pin}");
    if ($value === null) {
        echo "Requested pin doesn't exist in the app.";
        exit;
    }
    echo json_encode(explode(',', $value));
} else {
    echo json_encode($widget->getJsonValue());
}
--EXPECT--
