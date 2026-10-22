--TEST--
Translated seed (inferredbugs-csharp: WebSocketListener/1)
--FILE--
<?php
$endpoint = '127.0.0.1:8001';
$server = @stream_socket_server('tcp://' . $endpoint, $errno, $errstr);
if ($server === false) {
    exit(0);
}
stream_set_blocking($server, false);
$clients = [];
$start = microtime(true);
while (microtime(true) - $start < 0.2) {
    $read = [$server];
    foreach ($clients as $c) $read[] = $c;
    $write = $except = [];
    if (@stream_select($read, $write, $except, 0, 20000) === false) continue;
    foreach ($read as $r) {
        if ($r === $server) {
            $c = @stream_socket_accept($server, 0);
            if ($c !== false) {
                stream_set_blocking($c, false);
                $clients[] = $c;
            }
        } else {
            $data = @fread($r, 8192);
            if ($data === '' || $data === false) {
                $idx = array_search($r, $clients, true);
                if ($idx !== false) unset($clients[$idx]);
                @fclose($r);
                continue;
            }
            if ($data !== '') {
                $rev = strrev($data);
                @fwrite($r, $rev);
            }
        }
    }
}
foreach ($clients as $c) @fclose($c);
@fclose($server);
?>
--EXPECT--
