--TEST--
Translated seed (inferredbugs-csharp: Quic.NET/3)
--FILE--
<?php
echo "Starting client.\n";
$s = @stream_socket_client("tcp://127.0.0.1:11000", $e, $es, 1);
if ($s) {
    stream_set_blocking($s, false);
    fwrite($s, "Hello from client!");
    fclose($s);
}
echo "Done\n";
?>
--EXPECT--
