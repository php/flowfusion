--TEST--
Translated seed (inferredbugs-java: asterisk-java/37)
--FILE--
<?php

$MAX_VERSION_ATTEMPTS = 4;
$RECONNECTION_INTERVAL_1 = 50;
$RECONNECTION_INTERVAL_2 = 5000;

$state = 'INITIAL';
$hostname = 'localhost';
$port = 5038;
$timeout = 2000;
$version = null;

function connect($hostname, $port) {
    $socket = fsockopen($hostname, $port, $errno, $errstr);
    if (!$socket) {
        throw new Exception("Could not connect: $errstr ($errno)");
    }
    return $socket;
}

function sendAction($socket, $action) {
    fwrite($socket, $action . "\n");
    return fgets($socket);
}

function determineVersion($socket) {
    global $MAX_VERSION_ATTEMPTS, $timeout;
    $attempts = 0;

    while ($attempts < $MAX_VERSION_ATTEMPTS) {
        $response = sendAction($socket, 'core show version');
        if (preg_match('/Asterisk (?P<version>\d+\.\d+)/', $response, $matches)) {
            return $matches['version'];
        }
        usleep($RECONNECTION_INTERVAL_1 * 1000);
        $attempts++;
    }
    return '1.6'; // Default fallback version
}

$socket = null;
try {
    $socket = connect($hostname, $port);
    $version = determineVersion($socket);
    echo "Connected successfully. Asterisk version: $version\n";
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
} finally {
    if ($socket) {
        fclose($socket);
    }
}
?>
--EXPECT--
