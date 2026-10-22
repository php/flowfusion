--TEST--
Translated seed (inferredbugs-csharp: HttpMock/2)
--FILE--
<?php
$servers = [];
$uris = [
    "http://127.0.0.1:80/a",
    "http://127.0.0.1:80/b",
    "http://127.0.0.1:81/c",
    "http://localhost:81/d",
    "http://[::1]:80/e",
];

foreach ($uris as $u) {
    $p = parse_url($u);
    $port = $p['port'] ?? 80;
    if (!array_key_exists($port, $servers)) {
        $servers[$port] = ['uri' => $u, 'count' => 0];
    }
    $servers[$port]['count']++;
    $servers[$port]['path'] = $p['path'] ?? '/';
}

echo count($servers), "\n";
foreach ($servers as $port => $server) {
    echo $port, " ", $server['count'], " ", $server['path'], "\n";
}
?>
--EXPECT--
