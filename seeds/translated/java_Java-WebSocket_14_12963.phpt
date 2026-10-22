--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/14)
--FILE--
<?php

$address = '127.0.0.1';
$port = 8080;
$connections = [];
$server = stream_socket_server("tcp://$address:$port", $errno, $errstr);

if (!$server) {
    die("Error: $errstr ($errno)\n");
}

while (true) {
    $read = array_merge([$server], $connections);
    stream_select($read, $write = null, $except = null, null);

    foreach ($read as $socket) {
        if ($socket === $server) {
            $client = stream_socket_accept($server);
            $connections[] = $client;
        } else {
            $data = fread($socket, 1024);
            if ($data === false || $data === '') {
                fclose($socket);
                $key = array_search($socket, $connections);
                if ($key !== false) {
                    unset($connections[$key]);
                }
            } else {
                fwrite($socket, $data);
            }
        }
    }
}
--EXPECT--
