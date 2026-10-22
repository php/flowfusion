--TEST--
Translated seed (inferredbugs-csharp: netmq/1)
--FILE--
<?php
$ctx = stream_context_create();
$server = stream_socket_server("tcp://127.0.0.1:8000", $errno, $errstr);
if (!$server) exit(1);

$pid = pcntl_fork();
if ($pid === -1) exit(1);

if ($pid === 0) {
    usleep(200000);
    $c = stream_socket_client("tcp://127.0.0.1:8000", $errno, $errstr, 1);
    if ($c) {
        fwrite($c, "hello message");
        fclose($c);
    }
    exit(0);
}

$client = stream_socket_accept($server, 5);
if ($client) {
    $data = '';
    while (!feof($client)) {
        $data .= fread($client, 8192);
    }
    echo $data, PHP_EOL;
    fclose($client);
}

pcntl_wait($status);
fclose($server);
--EXPECT--
