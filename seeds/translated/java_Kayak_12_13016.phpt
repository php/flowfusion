--TEST--
Translated seed (inferredbugs-java: Kayak/12)
--FILE--
<?php

$host = 'localhost'; // example host
$port = 12345; // example port
$busName = 'bus1';
$connected = false;
$socket = null;

function connect($host, $port) {
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        return null;
    }
    $result = socket_connect($socket, $host, $port);
    return $result ? $socket : null;
}

function send($socket, $message) {
    if ($socket) {
        socket_write($socket, $message, strlen($message));
    }
}

function receive($socket) {
    if ($socket) {
        $buf = '';
        socket_recv($socket, $buf, 2048, 0);
        return $buf;
    }
    return null;
}

$socket = connect($host, $port);
if ($socket) {
    $connected = true;
    $ret = receive($socket);
    if ($ret !== "< hi >") {
        echo "Did not receive greeting from host.\n";
    }

    send($socket, "< open $busName >");
    $ret = receive($socket);
    if ($ret !== "< ok >") {
        echo "Could not open bus\n";
    }

    $id = 0x1A; // example ID
    $sec = 0; // example seconds
    $usec = 0; // example microseconds

    send($socket, "< subscribe $sec $usec " . dechex($id) . " >");

    $frame = receive($socket);
    $fields = explode(" ", $frame);

    if ($fields[1] === "frame") {
        // Simulating frame processing
        $identifier = hexdec($fields[2]);
        $length = intval($fields[3]);
        $data = implode(array_slice($fields, 4, -1));
        // Do something with the identifier, length, and data...
    } elseif ($fields[1] === "error") {
        echo "Received error from socketcand: $frame\n";
    }

    socket_close($socket);
    $connected = false;
} else {
    echo "Could not connect to socket\n";
}
--EXPECT--
