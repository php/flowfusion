--TEST--
Translated seed (inferredbugs-csharp: ForgeNetworkingRemastered/1)
--FILE--
<?php
$host = '127.0.0.1';
$port = 12345;
$server = stream_socket_server("tcp://$host:$port", $errno, $errstr);
if ($server === false) {
    exit(0);
}
$pid = pcntl_fork();
if ($pid === 0) {
    $conn = @stream_socket_accept($server, 5);
    if ($conn) {
        $data = fread($conn, 8192);
        fwrite($conn, "OK");
        fclose($conn);
    }
    fclose($server);
    exit(0);
}
usleep(100000);
$client = stream_socket_client("tcp://$host:$port", $errno, $errstr, 5);
if ($client === false) {
    fclose($server);
    pcntl_wait($status);
    exit(0);
}
stream_set_blocking($client, false);
fwrite($client, "HELLO");
usleep(100000);
$buf = '';
$meta = stream_get_meta_data($client);
if (!empty($meta['unread_bytes'])) {
    $buf = fread($client, $meta['unread_bytes']);
}
fclose($client);
pcntl_wait($status);
fclose($server);
?>
--EXPECT--
