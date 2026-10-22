--TEST--
Translated seed (inferredbugs-csharp: SuperSocket.ClientEngine/1)
--FILE--
<?php
$targets = [
    ['host' => '127.0.0.1', 'port' => 1],
    ['host' => '::1', 'port' => 1],
];

foreach ($targets as $t) {
    $errno = 0;
    $errstr = '';
    $fp = @stream_socket_client(
        'tcp://' . $t['host'] . ':' . $t['port'],
        $errno,
        $errstr,
        0.01,
        STREAM_CLIENT_CONNECT
    );
    if (is_resource($fp)) {
        fclose($fp);
    }
}
?>
--EXPECT--
