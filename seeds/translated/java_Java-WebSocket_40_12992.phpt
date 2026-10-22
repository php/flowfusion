--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/40)
--FILE--
<?php
$address = "127.0.0.1:8080";
$server = stream_socket_server("tcp://$address", $errno, $errstr);
if (!$server) {
    die("Error: $errstr ($errno)\n");
}

$connections = [];
$buffers = [];

while (true) {
    $read = $connections;
    $read[] = $server;
    if (stream_select($read, $write = null, $except = null, 0) === false) {
        break;
    }

    foreach ($read as $socket) {
        if ($socket === $server) {
            $client = stream_socket_accept($server);
            $connections[] = $client;
        } else {
            $data = fread($socket, 1024);
            if ($data === '' || $data === false) {
                fclose($socket);
                $connections = array_filter($connections, fn($conn) => $conn !== $socket);
            } else {
                $buffers[] = $data;
                fwrite($socket, "Echo: " . $data);
            }
        }
    }
}
fclose($server);
--EXPECT--
