--TEST--
Translated seed (inferredbugs-csharp: socket.core/1)
--FILE--
<?php
$server = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
if (!$server) exit(1);
$addr = stream_socket_get_name($server, false);
$port = (int)substr(strrchr($addr, ':'), 1);

$pid = pcntl_fork();
if ($pid === 0) {
    $conn = stream_socket_accept($server, 5);
    if ($conn) {
        $data = fread($conn, 8192);
        if ($data !== '' && $data !== false) fwrite($conn, $data);
        fclose($conn);
    }
    fclose($server);
    exit(0);
}

$client = stream_socket_client("tcp://127.0.0.1:$port", $errno, $errstr, 5);
if (!$client) exit(1);

stream_set_blocking($client, false);
$payload = str_repeat("A", 1) . "B";
fwrite($client, $payload);
usleep(100000);

$read = '';
for ($i = 0; $i < 10; $i++) {
    $chunk = fread($client, 8192);
    if ($chunk !== false && $chunk !== '') $read .= $chunk;
    if (strlen($read) >= strlen($payload)) break;
    usleep(10000);
}

fclose($client);
pcntl_wait($status);
fclose($server);
?>
--EXPECT--
