--TEST--
Translated seed (inferredbugs-csharp: netDumbster/1)
--FILE--
<?php
$port = 2525;
$server = stream_socket_server("tcp://127.0.0.1:$port", $errno, $errstr);
if (!$server) exit(1);
stream_set_blocking($server, false);

$messages = [];
for ($i = 0; $i < 2; $i++) {
    $client = stream_socket_client("tcp://127.0.0.1:$port", $errno, $errstr, 1);
    if (!$client) exit(1);
    fwrite($client, "DATA $i\n");
    fclose($client);
    $conn = @stream_socket_accept($server, 1);
    if ($conn) {
        $messages[] = trim(stream_get_contents($conn));
        fclose($conn);
    }
}

fclose($server);

$server = stream_socket_server("tcp://127.0.0.1:$port", $errno, $errstr);
if (!$server) exit(1);
stream_set_blocking($server, false);

$client = stream_socket_client("tcp://127.0.0.1:$port", $errno, $errstr, 1);
if (!$client) exit(1);
fwrite($client, "αβγ\nline2\nline3");
fclose($client);
$conn = @stream_socket_accept($server, 1);
if ($conn) {
    $body = stream_get_contents($conn);
    fclose($conn);
}
fclose($server);

$server = stream_socket_server("tcp://127.0.0.1:0", $errno, $errstr);
if (!$server) exit(1);
$addr = stream_socket_get_name($server, false);
fclose($server);

echo count($messages) . "\n";
echo $body . "\n";
echo $addr . "\n";
?>
--EXPECT--
