--TEST--
Translated seed (inferredbugs-csharp: amqpnetlite/2)
--FILE--
<?php
$socket = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
if ($socket === false) exit(1);

[$server, $client] = $socket;

stream_set_blocking($server, true);
stream_set_blocking($client, true);

$msg = "Hello Pipe!";
fwrite($client, $msg);
fclose($client);

$buf = '';
while (!feof($server)) {
    $chunk = fread($server, 8192);
    if ($chunk === false) break;
    $buf .= $chunk;
    if ($buf !== '') break;
}

echo "Listener: received '" . $buf . "'.\n";
fclose($server);

echo "Client: closed\n";
echo "Listener: closed\n";
?>
--EXPECT--
