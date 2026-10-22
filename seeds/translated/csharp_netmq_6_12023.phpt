--TEST--
Translated seed (inferredbugs-csharp: netmq/6)
--FILE--
<?php
$port = 5555;
$s = @stream_socket_server("tcp://127.0.0.1:$port", $errno, $errstr, STREAM_SERVER_BIND | STREAM_SERVER_LISTEN);
if (!$s) {
    exit(0);
}
stream_set_blocking($s, false);
$c = @stream_socket_client("tcp://127.0.0.1:$port", $errno, $errstr, 1, STREAM_CLIENT_CONNECT);
if (!$c) {
    fclose($s);
    exit(0);
}
$r = @stream_socket_accept($s, 1);
fclose($s);
if (!$r) {
    fclose($c);
    exit(0);
}
stream_set_blocking($c, false);
stream_set_blocking($r, false);
fwrite($c, "\0");
$read = [$r];
$write = $except = [];
@stream_select($read, $write, $except, 0, 100000);
if ($read) {
    fread($r, 1);
}
fclose($c);
fclose($r);
?>
--EXPECT--
