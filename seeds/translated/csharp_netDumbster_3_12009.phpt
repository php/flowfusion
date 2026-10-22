--TEST--
Translated seed (inferredbugs-csharp: netDumbster/3)
--FILE--
<?php
$port = 2525;
$server = stream_socket_server("tcp://127.0.0.1:$port", $errno, $errstr);
if (!$server) {
    $port = 0;
    $server = stream_socket_server("tcp://127.0.0.1:0", $errno, $errstr);
}
$addr = stream_socket_get_name($server, false);
$port = (int)substr(strrchr($addr, ':'), 1);

$messages = [];
$handler = function ($conn) use (&$messages) {
    $data = '';
    while (!feof($conn)) {
        $line = fgets($conn);
        if ($line === false) break;
        $data .= $line;
        if (rtrim($line, "\r\n") === '.') break;
    }
    $messages[] = $data;
    fwrite($conn, "250 OK\r\n");
    fclose($conn);
};

for ($i = 0; $i < 2; $i++) {
    $client = stream_socket_client("tcp://127.0.0.1:$port", $errno, $errstr, 1);
    fwrite($client, "EHLO localhost\r\nMAIL FROM:<one@example.com>\r\nRCPT TO:<two@example.com>\r\nDATA\r\nSubject: test\r\n\r\nthis is the body\r\n.\r\nQUIT\r\n");
    $conn = stream_socket_accept($server, 1);
    if ($conn) $handler($conn);
    fclose($client);
}

fclose($server);
echo count($messages) . PHP_EOL;
?>
--EXPECT--
