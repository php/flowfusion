--TEST--
Translated seed (inferredbugs-java: Kayak/11)
--FILE--
<?php

$host = '127.0.0.1';
$port = 12345;
$connected = false;
$socket = null;

function connect(&$socket, $host, $port) {
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 1, 'usec' => 0));
    if (@socket_connect($socket, $host, $port) === false) {
        return false;
    }
    return true;
}

function send($socket, $message) {
    socket_write($socket, $message, strlen($message));
}

function getElement($socket) {
    $buf = '';
    $bytes = @socket_recv($socket, $buf, 2048, 0);
    return $bytes === false ? null : trim($buf);
}

if (connect($socket, $host, $port)) {
    $connected = true;

    $greeting = getElement($socket);
    if ($greeting !== "< hi >") {
        exit;
    }

    send($socket, "< open bus_name >");
    $response = getElement($socket);
    if ($response !== "< ok >") {
        exit;
    }

    send($socket, "< subscribe 0 0 0x1 >");
    
    while (true) {
        $frame = getElement($socket);
        if ($frame !== null) {
            $fields = preg_split('/\s+/', $frame);
            if ($fields[1] === "frame") {
                $data = implode('', array_slice($fields, 4, -1));
                $identifier = hexdec($fields[2]);
                $length = intval($fields[3]);
                // Simulate frame reception
            } elseif ($fields[1] === "error") {
                // Handle error
            }
        }
    }
    
    socket_close($socket);
}
--EXPECT--
