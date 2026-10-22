--TEST--
Translated seed (inferredbugs-csharp: TorSharp/1)
--FILE--
<?php
$pid = pcntl_fork();
if ($pid === 0) {
    $server = stream_socket_server('tcp://127.0.0.1:1337', $errno, $errstr);
    if (!$server) exit(1);
    $seen = 0;
    while ($conn = @stream_socket_accept($server, 30)) {
        $req = '';
        while (!feof($conn) && strpos($req, "\r\n\r\n") === false) $req .= fread($conn, 8192);
        $body = $seen++ === 0 ? "1.1.1.1\n" : "2.2.2.2\n";
        fwrite($conn, "HTTP/1.1 200 OK\r\nContent-Length: " . strlen($body) . "\r\nConnection: close\r\n\r\n" . $body);
        fclose($conn);
        if ($seen >= 2) break;
    }
    fclose($server);
    exit(0);
}

usleep(200000);

$ctx = stream_context_create(['http' => ['proxy' => 'tcp://127.0.0.1:1337', 'request_fulluri' => true]]);
echo file_get_contents('http://example.com/api/myip.php', false, $ctx), "\n";
echo file_get_contents('http://example.com/api/myip.php', false, $ctx), "\n";

pcntl_wait($status);
?>
--EXPECT--
