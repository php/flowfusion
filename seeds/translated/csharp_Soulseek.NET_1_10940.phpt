--TEST--
Translated seed (inferredbugs-csharp: Soulseek.NET/1)
--FILE--
<?php
$address = "127.0.0.1";
$port = 2242;

$server = @stream_socket_server("tcp://$address:$port", $errno, $errstr);
if ($server === false) {
    exit(0);
}

$pid = function_exists('pcntl_fork') ? pcntl_fork() : -1;
if ($pid === 0) {
    $client = @stream_socket_client("tcp://$address:$port", $errno, $errstr, 1);
    if ($client !== false) {
        $username = "user";
        $password = "pass";
        $request = pack("C", 1) . $username . "\0" . $password . "\0" . pack("V", 181) . md5($username . $password) . "\0" . pack("V", 1);
        fwrite($client, $request);
        fread($client, 4096);
        fclose($client);
    }
    exit(0);
}

$peer = @stream_socket_accept($server, 5);
if ($peer !== false) {
    $data = fread($peer, 4096);
    $reply = pack("C", 1) . "OK\0";
    fwrite($peer, $reply);
    fclose($peer);
}

if ($pid > 0) {
    pcntl_wait($status);
}

fclose($server);
--EXPECT--
