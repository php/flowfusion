--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/12)
--FILE--
<?php
$nodes = [
    ['Hostname' => '127.0.0.1:8091', 'Ports' => ['Direct' => 11210]],
    ['Hostname' => 'example.com:8091', 'Ports' => ['Direct' => 11211]],
    ['Hostname' => 'localhost:8091', 'Ports' => ['Direct' => 0]],
];

foreach ($nodes as $node) {
    $server = str_replace('8091', (string)$node['Ports']['Direct'], $node['Hostname']);
    $parts = parse_url('tcp://' . $server);
    $host = $parts['host'] ?? '';
    $port = $parts['port'] ?? 0;
    $endpoint = $host . ':' . $port;
    $pool = ['config' => [], 'endpoint' => $endpoint];
    $ioStrategy = ['pool' => $pool, 'type' => 'async'];
    if ($ioStrategy['pool']['endpoint'] === '') {
        throw new RuntimeException('invalid endpoint');
    }
}
?>
--EXPECT--
