--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/77)
--FILE--
<?php
$server = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
$addr = stream_socket_get_name($server, false);
$port = substr(strrchr($addr, ':'), 1);
$pid = pcntl_fork();
if ($pid === 0) {
    $seen = 0;
    while ($seen < 4) {
        $c = @stream_socket_accept($server, 5);
        if (!$c) continue;
        $req = '';
        while (!feof($c) && strpos($req, "\r\n\r\n") === false) $req .= fread($c, 8192);
        $seen++;
        $path = preg_match('#^[A-Z]+\s+(\S+)#', $req, $m) ? $m[1] : '/';
        $code = (strpos($path, '/fail') !== false) ? 500 : 200;
        $body = "ok";
        fwrite($c, "HTTP/1.1 $code " . ($code === 200 ? "OK" : "ERR") . "\r\nContent-Length: " . strlen($body) . "\r\nConnection: close\r\n\r\n$body");
        fclose($c);
    }
    fclose($server);
    exit(0);
}

$events = [];
$start = microtime(true);
for ($i = 0; $i < 3; $i++) {
    $ctx = stream_context_create(['http' => ['method' => 'GET', 'timeout' => 2, 'ignore_errors' => true]]);
    $u = "http://127.0.0.1:$port/?i=$i";
    $r = @file_get_contents($u, false, $ctx);
    $events[] = [$u, $http_response_header[0] ?? '', strlen((string)$r), microtime(true) - $start];
}
$ctx = stream_context_create(['http' => ['method' => 'POST', 'content' => 'foo', 'timeout' => 2, 'ignore_errors' => true]]);
$u = "http://127.0.0.1:$port/fail";
$r = @file_get_contents($u, false, $ctx);
$events[] = [$u, $http_response_header[0] ?? '', strlen((string)$r), microtime(true) - $start];

echo count($events) . "\n";
foreach ($events as $e) echo implode(" | ", $e) . "\n";
pcntl_wait($status);
?>
--EXPECT--
