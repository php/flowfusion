--TEST--
Translated seed (inferredbugs-csharp: csredis/1)
--FILE--
<?php
$host = '127.0.0.1';
$port = 0;
$s = @stream_socket_server("tcp://$host:$port", $errno, $errstr);
if (!$s) exit(0);
$addr = stream_socket_get_name($s, false);
$port = (int)substr(strrchr($addr, ':'), 1);
$c = @stream_socket_client("tcp://$host:$port", $errno, $errstr, 1);
if (!$c) exit(0);
stream_set_blocking($c, false);
stream_set_blocking($s, false);
$peer = @stream_socket_accept($s, 0);
if ($peer) {
    fwrite($peer, str_repeat("x", 8192));
    fclose($peer);
}
$buf = '';
for ($i = 0; $i < 3; $i++) {
    $r = [$c];
    $w = $e = null;
    @stream_select($r, $w, $e, 0, 200000);
    $chunk = @fread($c, 4096);
    if ($chunk !== false && $chunk !== '') $buf .= $chunk;
}
fclose($c);
fclose($s);
echo strlen($buf) . "\n";
?>
--EXPECT--
