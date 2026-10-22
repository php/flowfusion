--TEST--
Translated seed (inferredbugs-csharp: StackExchange.Redis/1)
--FILE--
<?php
$server = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
$addr = stream_socket_get_name($server, false);
$client = stream_socket_client("tcp://$addr", $errno, $errstr, 1);
$peer = stream_socket_accept($server, 1);
fwrite($client, str_repeat("x", 8192));
$r = [$peer];
$w = $e = [];
$n = stream_select($r, $w, $e, 0, 100000);
if ($n > 0) {
    fread($peer, 8192);
}
fclose($client);
fclose($peer);
fclose($server);
?>
--EXPECT--
