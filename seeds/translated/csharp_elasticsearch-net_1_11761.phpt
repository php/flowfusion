--TEST--
Translated seed (inferredbugs-csharp: elasticsearch-net/1)
--FILE--
<?php
$host = '127.0.0.1';
$port = 8080;
$server = stream_socket_server("tcp://$host:$port", $errno, $errstr);
if (!$server) exit(0);

$pid = pcntl_fork();
if ($pid === 0) {
    $c = stream_socket_accept($server, 5);
    if ($c) {
        $req = '';
        while (!str_contains($req, "\r\n\r\n")) {
            $chunk = fread($c, 1024);
            if ($chunk === '' || $chunk === false) break;
            $req .= $chunk;
        }
        $body = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $resp = "HTTP/1.1 200 OK\r\nContent-Length: " . strlen($body) . "\r\nConnection: close\r\n\r\n" . $body;
        fwrite($c, $resp);
        fclose($c);
    }
    fclose($server);
    exit(0);
}

usleep(100000);

$ctx = stream_context_create(['http' => ['method' => 'GET', 'timeout' => 2]]);
$fp = fopen("http://$host:$port/test", 'r', false, $ctx);
if ($fp) {
    $out = '';
    while (!feof($fp)) {
        $out .= fread($fp, 7);
    }
    fclose($fp);
}

$ctx = stream_context_create(['http' => ['method' => 'POST', 'content' => str_repeat('x', 2048), 'timeout' => 2]]);
$fp = fopen("http://$host:$port/post", 'r', false, $ctx);
if ($fp) {
    while (!feof($fp)) fread($fp, 13);
    fclose($fp);
}

pcntl_wait($status);
?>
--EXPECT--
