--TEST--
Translated seed (inferredbugs-csharp: Quic.NET/4)
--FILE--
<?php
echo "Starting client.\n";
$fp = @stream_socket_client("tcp://127.0.0.1:11000", $errno, $errstr, 1);
if ($fp) {
    stream_set_timeout($fp, 1);
    fwrite($fp, "Hello from client!");
    fwrite($fp, "Hello from client2!");
    echo "Waiting for message from the server\n";
    for ($i = 0; $i < 2; $i++) {
        $data = @fread($fp, 8192);
        if ($data === false || $data === '') {
            $e = error_get_last();
            echo (($e['message'] ?? 'No data received') . "\n");
        } else {
            echo "Received: $data\n";
        }
    }
    fclose($fp);
} else {
    echo "Connect failed: $errstr ($errno)\n";
}
?>
--EXPECT--
