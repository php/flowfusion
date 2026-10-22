--TEST--
Translated seed (inferredbugs-csharp: SabberStone/1)
--FILE--
<?php
$server = stream_socket_server('tcp://0.0.0.0:9001', $errno, $errstr);
if ($server !== false) {
    stream_socket_close($server);
}
?>
--EXPECT--
