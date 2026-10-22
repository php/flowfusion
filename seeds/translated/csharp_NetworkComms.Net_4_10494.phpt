--TEST--
Translated seed (inferredbugs-csharp: NetworkComms.Net/4)
--FILE--
<?php
$u = parse_url($argv[1] ?? 'tcp://127.0.0.1:0');
$host = $u['host'] ?? '127.0.0.1';
$port = $u['port'] ?? 0;
$fp = @stream_socket_client("tcp://$host:$port", $e, $s, 0.2);
if ($fp) {
    stream_set_timeout($fp, 1);
    fwrite($fp, "x");
    fclose($fp);
}
$server = @stream_socket_server("tcp://127.0.0.1:0", $e, $s);
if ($server) {
    $name = stream_socket_get_name($server, false);
    $c = @stream_socket_client($name, $e2, $s2, 0.2);
    if ($c) {
        fwrite($c, "y");
        fclose($c);
    }
    fclose($server);
}
?>
--EXPECT--
