--TEST--
Translated seed (inferredbugs-csharp: netDumbster/5)
--FILE--
<?php
$port = 2525;
$server = stream_socket_server("tcp://127.0.0.1:$port", $errno, $errstr);
if (!$server) exit(1);
stream_set_blocking($server, false);

$messages = [];
for ($i = 0; $i < 2; $i++) {
    $client = stream_socket_client("tcp://127.0.0.1:$port", $errno, $errstr, 1);
    if (!$client) exit(1);
    fwrite($client, "EHLO localhost\r\nMAIL FROM:<one@example.com>\r\nRCPT TO:<two@example.com>\r\nDATA\r\nSubject: test\r\n\r\nthis is the body\r\n.\r\nQUIT\r\n");
    fclose($client);
    $conn = null;
    for ($j = 0; $j < 100; $j++) {
        $conn = @stream_socket_accept($server, 0);
        if ($conn) break;
        usleep(10000);
    }
    if (!$conn) exit(1);
    stream_set_timeout($conn, 1);
    $data = '';
    while (!feof($conn)) {
        $chunk = fread($conn, 8192);
        if ($chunk === '' || $chunk === false) break;
        $data .= $chunk;
        if (str_contains($data, "\r\n.\r\n")) break;
    }
    $messages[] = $data;
    fclose($conn);
}
fclose($server);
echo count($messages), "\n";
?>
--EXPECT--
