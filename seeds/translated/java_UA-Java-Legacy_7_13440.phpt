--TEST--
Translated seed (inferredbugs-java: UA-Java-Legacy/7)
--FILE--
<?php

$addr = "127.0.0.1:8080";
$socket = socket_create(AF_INET, SOCK_STREAM, 0);
socket_set_option($socket, SOL_SOCKET, SO_RCVBUF, 0);
socket_set_option($socket, SOL_SOCKET, SO_SNDBUF, 0);

if ($socket === false) {
    die("Socket creation failed: " . socket_strerror(socket_last_error()));
}

if (socket_connect($socket, explode(':', $addr)[0], explode(':', $addr)[1]) === false) {
    die("Socket connection failed: " . socket_strerror(socket_last_error($socket)));
}

$maxMessageSize = 1024; // Example max message size
$bufferSize = 512; // Example buffer size
$buffer = str_repeat('A', $bufferSize); // Fill the buffer with data
$chunks = ceil(strlen($buffer) / $maxMessageSize);

for ($i = 0; $i < $chunks; $i++) {
    $chunk = substr($buffer, $i * $maxMessageSize, $maxMessageSize);
    $length = strlen($chunk);
    if ($length > 0) {
        socket_write($socket, pack("N", $length) . $chunk, $length + 4);
    }
}

$response = socket_read($socket, 2048);
if ($response === false) {
    die("Socket read failed: " . socket_strerror(socket_last_error($socket)));
}

socket_close($socket);
--EXPECT--
