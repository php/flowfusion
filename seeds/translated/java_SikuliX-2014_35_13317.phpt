--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/35)
--FILE--
<?php

$port = 50000;
$server = stream_socket_server("tcp://localhost:$port", $errno, $errstr);
if (!$server) {
    die("Could not create server: $errstr ($errno)\n");
}

while ($client = @stream_socket_accept($server)) {
    fwrite($client, "Connected to server.\n");
    $running = true;
    while ($running) {
        $input = fgets($client);
        if ($input === false || trim($input) === "EXIT") {
            $running = false;
        } elseif (stripos($input, "STOP") === 0) {
            $running = false;
            fwrite($client, "Server will stop now.\n");
        } else {
            fwrite($client, "Received: $input");
        }
    }
    fclose($client);
}

fclose($server);
--EXPECT--
