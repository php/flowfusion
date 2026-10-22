--TEST--
Translated seed (inferredbugs-csharp: HttpMock/1)
--FILE--
<?php
$server = stream_socket_server('tcp://127.0.0.1:8081', $errno, $errstr);
if (!$server) exit(1);
stream_set_blocking($server, false);

$contexts = [];
$ctx1 = [
    'POST /firsttest' => "Response for first test",
];
$ctx2 = [
    'POST /secondtest' => "Response for second test",
];
$contexts[] = $ctx1;
$contexts[] = $ctx2;

$requests = [
    "POST /firsttest HTTP/1.1\r\nHost: localhost:8081\r\nConnection: close\r\nContent-Length: 0\r\n\r\n",
    "POST /secondtest HTTP/1.1\r\nHost: localhost:8081\r\nConnection: close\r\nContent-Length: 0\r\n\r\n",
];

foreach ($requests as $req) {
    $client = stream_socket_client('tcp://127.0.0.1:8081', $errno, $errstr, 1);
    if (!$client) exit(1);
    fwrite($client, $req);

    $conn = @stream_socket_accept($server, 1);
    if (!$conn) exit(1);
    $data = '';
    while (!feof($conn)) {
        $chunk = fread($conn, 8192);
        if ($chunk === '' || $chunk === false) break;
        $data .= $chunk;
        if (strpos($data, "\r\n\r\n") !== false) break;
    }

    $line = strtok($data, "\r\n");
    $method = strtok($line, ' ');
    $path = strtok(' ');
    $path = rtrim($path, '/');
    $key = $method . ' ' . $path;

    $body = '';
    foreach (array_reverse($contexts) as $ctx) {
        if (isset($ctx[$key])) {
            $body = $ctx[$key];
            break;
        }
    }

    fwrite($conn, "HTTP/1.1 200 OK\r\nContent-Length: " . strlen($body) . "\r\nConnection: close\r\n\r\n" . $body);
    fclose($conn);

    $resp = '';
    while (!feof($client)) {
        $chunk = fread($client, 8192);
        if ($chunk === '' || $chunk === false) break;
        $resp .= $chunk;
    }
    fclose($client);
}

fclose($server);
?>
--EXPECT--
