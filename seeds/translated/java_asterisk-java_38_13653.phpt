--TEST--
Translated seed (inferredbugs-java: asterisk-java/38)
--FILE--
<?php

define('RECONNECTION_INTERVAL_1', 50);
define('RECONNECTION_INTERVAL_2', 5000);
define('DEFAULT_HOSTNAME', 'localhost');
define('DEFAULT_PORT', 5038);
define('MAX_VERSION_ATTEMPTS', 4);
$showVersionPattern = "/^(core )?show version.*/";

$hostname = DEFAULT_HOSTNAME;
$port = DEFAULT_PORT;
$socket = null;

function connect($hostname, $port) {
    $socket = fsockopen($hostname, $port, $errno, $errstr, 30);
    if (!$socket) {
        throw new Exception("Unable to connect: $errstr ($errno)");
    }
    return $socket;
}

function sendCommand($socket, $command) {
    fwrite($socket, $command . "\n");
    $response = '';
    while (!feof($socket)) {
        $response .= fgets($socket, 128);
        if (preg_match($GLOBALS['showVersionPattern'], $response)) {
            break;
        }
    }
    return $response;
}

function main() {
    global $hostname, $port, $socket;
    $attempts = 0;

    while ($attempts < MAX_VERSION_ATTEMPTS) {
        try {
            $socket = connect($hostname, $port);
            $response = sendCommand($socket, 'show version');
            echo $response;
            fclose($socket);
            break;
        } catch (Exception $e) {
            if ($attempts < 10) {
                usleep(RECONNECTION_INTERVAL_1 * 1000);
            } else {
                usleep(RECONNECTION_INTERVAL_2 * 1000);
            }
            $attempts++;
        }
    }

    if ($attempts >= MAX_VERSION_ATTEMPTS) {
        echo "Unable to determine version, assuming default behavior.";
    }
}

main();
--EXPECT--
