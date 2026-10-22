--TEST--
Translated seed (inferredbugs-java: asterisk-java/42)
--FILE--
<?php

$monitorChannel1 = true;
$monitorChannel2 = true;
$hungup = false;
$newChannel = null;
$originateSuccess = false;

function originate($local, $target, $myVars, $timeout = null, $hideCallerId = false, $context) {
    global $originateSuccess, $newChannel, $monitorChannel1, $monitorChannel2, $hungup;
    
    if ($hungup) {
        return null;
    }

    $channelId = time() . ".AJ" . uniqid();
    
    if ($timeout === null) {
        $timeout = 30;
    }

    // Simulating the response of the originate action
    $response = random_int(0, 1) ? "Success" : "Error";

    if (strcasecmp($response, "Success") !== 0) {
        throw new Exception("Error Originating call: " . $response);
    }

    // Simulate some event handling
    if ($monitorChannel1) {
        $originateSuccess = true;
        $newChannel = "ChannelCreated";
    }

    // Simulate waiting for response
    sleep($timeout);

    if ($originateSuccess) {
        echo "New channel ok: " . $newChannel;
    } else {
        echo "Originate failed to connect endPoint: " . $local . " to ext " . $target;
    }
}

$local = "localEndpoint";
$target = "targetEndpoint";
$myVars = [];
$timeout = 5; // in seconds
$context = "default";

originate($local, $target, $myVars, $timeout, false, $context);
--EXPECT--
