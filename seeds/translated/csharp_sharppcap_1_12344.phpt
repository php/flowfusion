--TEST--
Translated seed (inferredbugs-csharp: sharppcap/1)
--FILE--
<?php
$devices = @net_get_interfaces();
if ($devices === false || !$devices) {
    exit(0);
}

$dev = null;
foreach ($devices as $name => $info) {
    if ($name === 'lo' || $name === 'any') {
        $dev = $name;
    }
}

if ($dev === null) {
    $keys = array_keys($devices);
    $dev = $keys[0];
}

$socket = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
if ($socket !== false) {
    @socket_bind($socket, '0.0.0.0', 0);
    @socket_getsockname($socket, $addr, $port);
    @socket_close($socket);
}

$fp = @fopen('php://temp', 'r+');
if ($fp !== false) {
    fwrite($fp, "statistics");
    rewind($fp);
    stream_get_contents($fp);
    fclose($fp);
}

try {
    @count(null);
} catch (Throwable $e) {
}

echo "Found " . count($devices) . " devices\n";
echo $dev . "\n";
?>
--EXPECT--
