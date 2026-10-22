--TEST--
Translated seed (inferredbugs-java: blynk-server/23)
--FILE--
<?php

$token = "exampleToken";
$pinString = "A0";
$pinValues = ["value1", "value2"];

$tokenValue = getTokenValueByToken($token);

if ($tokenValue === null) {
    echo "Invalid token.";
    exit;
}

$user = $tokenValue->user;
$dashId = $tokenValue->dashId;
$deviceId = $tokenValue->deviceId;

$dash = $user->profile->getDashById($dashId);

if ($dash === null || !$dash->isActive) {
    echo "Project is not active.";
    exit;
}

try {
    $pinType = getPinType($pinString[0]);
    $pin = intval(substr($pinString, 1));
} catch (Exception $e) {
    echo "Wrong pin format.";
    exit;
}

$now = time();

$pinValue = implode("|", $pinValues);
reportData($user, $dashId, $deviceId, $pin, $pinType, $pinValue, $now);

$body = makeBody($dash, $deviceId, $pin, $pinType, $pinValue);

$session = getSession($user);
if ($session === null) {
    echo "No session for user.";
    exit;
}

sendMessageToHardware($session, $dashId, $body, $deviceId);

if ($dash->isActive) {
    sendToApps($session, $dashId, $body);
}

function getTokenValueByToken($token) {
    // Simulated function to retrieve token value
    return (object) ['user' => (object) ['profile' => new Profile()], 'dashId' => 1, 'deviceId' => 1];
}

function getPinType($char) {
    // Simulated function to get pin type
    return "VIRTUAL";
}

function reportData($user, $dashId, $deviceId, $pin, $pinType, $pinValue, $now) {
    // Simulated function to report data
}

function makeBody($dash, $deviceId, $pin, $pinType, $pinValue) {
    return "Body content for device $deviceId, pin $pin, type $pinType, value $pinValue";
}

function getSession($user) {
    // Simulated function to retrieve user session
    return (object) [];
}

function sendMessageToHardware($session, $dashId, $body, $deviceId) {
    // Simulated function to send message to hardware
}

function sendToApps($session, $dashId, $body) {
    // Simulated function to send data to apps
}
--EXPECT--
