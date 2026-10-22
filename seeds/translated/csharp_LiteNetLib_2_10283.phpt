--TEST--
Translated seed (inferredbugs-csharp: LiteNetLib/2)
--FILE--
<?php
$server = stream_socket_server('tcp://127.0.0.1:9050', $errno, $errstr);
if (!$server) exit(1);

$client = stream_socket_client('tcp://127.0.0.1:9050', $errno, $errstr, 1);
if (!$client) exit(1);

$peer = @stream_socket_accept($server, 1);
if ($peer) {
    $data = str_repeat("\0", 13218);
    $data[0] = chr(192);
    $data[13217] = chr(31);
    fwrite($client, $data);
    $recv = '';
    while (strlen($recv) < 13218) {
        $chunk = fread($peer, 13218 - strlen($recv));
        if ($chunk === '' || $chunk === false) break;
        $recv .= $chunk;
    }
    if (strlen($recv) === 13218) {
        $echo = $recv[0] . $recv[13217];
    }
    fwrite($peer, $recv);
    fclose($peer);
}

fwrite($client, "HELLO! TST!");
fclose($client);
fclose($server);
?>
--EXPECT--
