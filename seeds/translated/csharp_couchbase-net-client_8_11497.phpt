--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/8)
--FILE--
<?php
$server = '127.0.0.1:8091';
$port = 11210;
$server = str_replace('8091', (string)$port, $server);
$parts = parse_url('tcp://' . $server);
$host = $parts['host'] ?? '127.0.0.1';
$port = $parts['port'] ?? 0;
$fp = @fsockopen($host, $port, $errno, $errstr, 0.01);
if (is_resource($fp)) {
    fclose($fp);
}
?>
--EXPECT--
