--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/16)
--FILE--
<?php
$address = '127.0.0.1:8080';
$conn = stream_socket_server("tcp://$address", $errno, $errstr);
if (!$conn) {
    die("Could not create server: $errstr ($errno)");
}
$connections = [];
$socket = $conn;
stream_set_blocking($socket, false);

while (true) {
    $read = array_merge([$socket], $connections);
    $write = [];
    $except = null;

    if (stream_select($read, $write, $except, 0) > 0) {
        foreach ($read as $r) {
            if ($r === $socket) {
                $new_conn = stream_socket_accept($socket);
                if ($new_conn) {
                    $connections[] = $new_conn;
                }
            } else {
                $data = fread($r, 1024);
                if ($data === '' || $data === false) {
                    fclose($r);
                    $connections = array_filter($connections, fn($c) => $c !== $r);
                }
            }
        }
    }
}
--EXPECT--
