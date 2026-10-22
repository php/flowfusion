--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/13)
--FILE--
<?php
$node = (object)[
    'Hostname' => '127.0.0.1:8091',
    'Ports' => (object)['Direct' => 11210]
];

$server = str_replace('8091', (string)$node->Ports->Direct, $node->Hostname);
$parts = parse_url('tcp://' . $server);
$host = $parts['host'] ?? '127.0.0.1';
$port = $parts['port'] ?? 0;

$fp = @stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, 0.01);
if (is_resource($fp)) {
    fclose($fp);
}
?>
--EXPECT--
