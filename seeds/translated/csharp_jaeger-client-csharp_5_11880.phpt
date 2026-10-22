--TEST--
Translated seed (inferredbugs-csharp: jaeger-client-csharp/5)
--FILE--
<?php
$uri = 'http://127.0.0.1:1/';
$headers = [
    'Accept' => 'application/vnd.apache.thrift.binary',
    'User-Agent' => 'THttpClientTransport/1.0.0',
    'X-Empty' => '',
    'X-Dup' => 'a',
    'x-dup' => 'b',
];

$ctx = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => implode("\r\n", array_map(
            fn($k, $v) => $k . ': ' . $v,
            array_keys($headers),
            array_values($headers)
        )),
        'content' => '',
        'timeout' => 1,
        'ignore_errors' => true,
    ],
]);

$body = '';
for ($i = 0; $i < 3; $i++) {
    $body .= chr(0x7f - $i) . str_repeat("A", $i);
}

$fp = @fopen($uri, 'r', false, $ctx);
if ($fp !== false) {
    @stream_get_contents($fp);
    @fclose($fp);
}

$mem = fopen('php://temp', 'r+');
fwrite($mem, $body);
rewind($mem);
stream_copy_to_stream($mem, fopen('php://temp', 'r+'));
fclose($mem);
?>
--EXPECT--
