--TEST--
Translated seed (inferredbugs-csharp: influxdb-client-csharp/4)
--FILE--
<?php
$u = parse_url('http://localhost:9999');
$scheme = $u['scheme'] ?? 'http';
$host = $u['host'] ?? 'localhost';
$port = $u['port'] ?? 80;
$user = 'my-user';
$pass = implode('', ['my', '-', 'password']);
$location = 'Medical Corp' . gmdate('Y-m-d H:i:s.v');
echo $scheme . '://' . $host . ':' . $port . ' ' . $user . ' ' . $pass . ' ' . $location . PHP_EOL;
?>
--EXPECT--
