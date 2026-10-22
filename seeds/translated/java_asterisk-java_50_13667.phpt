--TEST--
Translated seed (inferredbugs-java: asterisk-java/50)
--FILE--
<?php

$monitorChannel1 = null;
$monitorChannel2 = null;
$originateID = uniqid();
$originateSuccess = false;
$newChannel = null;
$hungup = false;
$channelId = time() . '.AJ' . mt_rand();
$localTimeout = 30000; // 30 seconds
$myVars = [];
$callerID = 'CallerID';
$hideCallerId = false;
$context = 'default';

function sendAction($action, $timeout) {
    // Simulated response for the sake of having a runnable example
    return ['response' => 'Success', 'message' => ''];
}

function hangup($channel) {
    // Simulated hangup
}

$response = null;

if ($hungup) {
    exit;
}

$response = sendAction(['action' => 'Originate', 'channelId' => $channelId, 'context' => $context, 'extension' => 'target', 'timeout' => $localTimeout], $localTimeout);

if (strcasecmp($response['response'], 'Success') !== 0) {
    throw new Exception($response['message']);
}

sleep(1); // Simulate waiting for response

if ($originateSuccess) {
    // Handle success
} else {
    if ($newChannel) {
        hangup($newChannel);
    }
}

if ($monitorChannel1) {
    // Simulate monitoring channel hangup
}

if ($monitorChannel2) {
    // Simulate monitoring channel hangup
}
--EXPECT--
