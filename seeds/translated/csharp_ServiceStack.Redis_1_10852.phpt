--TEST--
Translated seed (inferredbugs-csharp: ServiceStack.Redis/1)
--FILE--
<?php
$host = getenv('REDIS_HOST') ?: '127.0.0.1';
$port = (int)(getenv('REDIS_PORT') ?: 6379);
$password = getenv('REDIS_PASSWORD') ?: '';
$clients = 64;
$data = json_encode(array_map(fn($x) => ['i' => $x, 's' => str_repeat((string)$x, 3)], range(0, 99)));

for ($i = 0; $i < $clients; $i++) {
    $fp = @fsockopen($host, $port, $errno, $errstr, 2.0);
    if (!$fp) continue;
    stream_set_timeout($fp, 2);
    $cmd = "*2\r\n\$4\r\nAUTH\r\n\$" . strlen($password) . "\r\n" . $password . "\r\n";
    fwrite($fp, $cmd);
    fgets($fp);
    $key = "test:$host:$i";
    $payload = "*3\r\n\$3\r\nSET\r\n\$" . strlen($key) . "\r\n" . $key . "\r\n\$" . strlen($data) . "\r\n" . $data . "\r\n";
    fwrite($fp, $payload);
    fgets($fp);
    $get = "*2\r\n\$3\r\nGET\r\n\$" . strlen($key) . "\r\n" . $key . "\r\n";
    fwrite($fp, $get);
    fgets($fp);
    fclose($fp);
}
?>
--EXPECT--
