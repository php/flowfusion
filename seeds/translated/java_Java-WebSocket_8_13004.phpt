--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/8)
--FILE--
<?php

$address = '127.0.0.1';
$port = 8080;

$connections = [];

$server = stream_socket_server("tcp://$address:$port", $errno, $errstr);

if ($server === false) {
    die("Error: $errstr ($errno)\n");
}

stream_set_blocking($server, false);

while (true) {
    $readSockets = array_merge([$server], $connections);
    $writeSockets = [];
    $exceptSockets = null;

    if (stream_select($readSockets, $writeSockets, $exceptSockets, null) === false) {
        break;
    }

    foreach ($readSockets as $socket) {
        if ($socket === $server) {
            $client = stream_socket_accept($server);
            stream_set_blocking($client, false);
            $connections[] = $client;
        } else {
            $data = fread($socket, 1024);
            if ($data === "" || $data === false) {
                fclose($socket);
                $connections = array_filter($connections, function($s) use ($socket) {
                    return $s !== $socket;
                });
            } else {
                fwrite($socket, "Echo: $data");
            }
        }
    }
}

fclose($server);
--EXPECT--
