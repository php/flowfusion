--TEST--
Translated seed (inferredbugs-csharp: HyperFastCgi/2)
--FILE--
<?php
$server = ['keepAlive' => true, 'useThreadPool' => true];
$listener = @stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
if ($listener) {
    stream_set_blocking($listener, false);
    $name = stream_socket_get_name($listener, false);
    $port = (int)substr(strrchr($name, ':'), 1);
    $client = @stream_socket_client("tcp://127.0.0.1:$port", $e, $s, 1);
    $conn = @stream_socket_accept($listener, 1);
    if ($conn) {
        fwrite($conn, "ping");
        fclose($conn);
    }
    if ($client) {
        fread($client, 4);
        fclose($client);
    }
    fclose($listener);
}
echo "ok\n";
?>
--EXPECT--
