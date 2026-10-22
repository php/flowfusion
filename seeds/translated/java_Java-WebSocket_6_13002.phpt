--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/6)
--FILE--
<?php
$address = '127.0.0.1';
$port = 8080;
$connections = [];
$server = stream_socket_server("tcp://$address:$port", $errno, $errstr);
if (!$server) {
    die("$errstr ($errno)\n");
}

while (true) {
    $read = $connections;
    $read[] = $server;
    if (stream_select($read, $write = null, $except = null, 0) > 0) {
        if (in_array($server, $read)) {
            $client = stream_socket_accept($server);
            $connections[] = $client;
            $key = array_search($server, $read);
            unset($read[$key]);
        }
        foreach ($read as $client) {
            $data = fread($client, 1024);
            if ($data === false || $data === '') {
                fclose($client);
                $connections = array_filter($connections, function($c) use ($client) {
                    return $c !== $client;
                });
            } else {
                fwrite($client, "You said: $data");
            }
        }
    }
}
--EXPECT--
