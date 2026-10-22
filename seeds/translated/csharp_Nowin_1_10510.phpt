--TEST--
Translated seed (inferredbugs-csharp: Nowin/1)
--FILE--
<?php
$server = stream_socket_server('tcp://127.0.0.1:8080', $errno, $errstr);
if (!$server) exit(1);
stream_socket_shutdown($server, STREAM_SHUT_RDWR);
fclose($server);

$cases = [
    ['GET', '/', '', '', false, false],
    ['GET', '/path?query', '/path', 'query', false, false],
    ['GET', '/pathBase/path?query', '/pathBase/path', 'query', false, false],
    ['GET', '/SubPath?QueryString', '/SubPath', 'QueryString', false, false],
    ['POST', '/SubPath?QueryString', '/SubPath', 'QueryString', true, false],
];

foreach ($cases as $c) {
    $u = parse_url('http://localhost:8080' . $c[1]);
    $path = $u['path'] ?? '/';
    $query = $u['query'] ?? '';
    $req = $c[0] . ' ' . $path . ($query !== '' ? '?' . $query : '') . " HTTP/1.1\r\nHost: localhost:8080\r\nConnection: close\r\n";
    if ($c[4]) $req .= "Content-Length: 11\r\nContent-Type: text/plain; charset=utf-8\r\nExpect: 100-continue\r\n";
    $req .= "\r\n";
    if ($c[4]) $req .= "Hello World";
    $s = stream_socket_client('tcp://127.0.0.1:8080', $e, $es);
    if ($s) {
        fwrite($s, $req);
        while (!feof($s)) fread($s, 8192);
        fclose($s);
    }
}

$body = "Hello World";
$reqs = [
    "POST / HTTP/1.1\r\nHost: localhost:8080\r\nConnection: close\r\nContent-Length: 0\r\nContent-Type: text/plain; charset=utf-8\r\n\r\n",
    "POST / HTTP/1.1\r\nHost: localhost:8080\r\nConnection: close\r\nContent-Length: " . strlen($body) . "\r\nContent-Type: text/plain; charset=utf-8\r\n\r\n$body",
    "POST / HTTP/1.1\r\nHost: localhost:8080\r\nConnection: close\r\nTransfer-Encoding: chunked\r\nContent-Type: text/plain; charset=utf-8\r\n\r\n0\r\n\r\n",
    "POST / HTTP/1.1\r\nHost: localhost:8080\r\nConnection: close\r\nTransfer-Encoding: chunked\r\nContent-Type: text/plain; charset=utf-8\r\n\r\nb\r\n$body\r\n0\r\n\r\n",
];
foreach ($reqs as $req) {
    $s = stream_socket_client('tcp://127.0.0.1:8080', $e, $es);
    if ($s) {
        fwrite($s, $req);
        while (!feof($s)) fread($s, 8192);
        fclose($s);
    }
}
?>
--EXPECT--
