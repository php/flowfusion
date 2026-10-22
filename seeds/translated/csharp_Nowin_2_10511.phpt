--TEST--
Translated seed (inferredbugs-csharp: Nowin/2)
--FILE--
<?php
$h = fopen('php://temp', 'w+');
fwrite($h, str_repeat('A', 10));
rewind($h);
$meta = stream_get_meta_data($h);
$len = ftell($h);
$buf = stream_get_contents($h);
$parts = parse_url('http://localhost:8080/SubPath?QueryString');
$path = $parts['path'] ?? '';
$query = $parts['query'] ?? '';
$headers = [
    'host' => ['localhost:8080'],
    'content-length' => ['10'],
    'transfer-encoding' => ['chunked'],
    'expect' => ['100-continue'],
    'content-type' => ['text/plain; charset=utf-8'],
];
$responseHeaders = [];
$responseHeaders['Custom1'] = ['value1a', 'value1b'];
$responseHeaders['Custom2'] = ['value2a, value2b'];
$responseHeaders['Custom3'] = ['value3a, value3b', 'value3c'];
$responseHeaders['KEEP-alive'] = ['TRUE'];
$responseHeaders['content-length'] = ['0'];
$responseHeaders['www-Authenticate'] = ['Basic', 'NTLM'];
$responseHeaders['Transfer-Encoding'] = ['ChUnKed'];
$responseHeaders['CONNECTION'] = ['ClOsE'];
$reason = "Hello World";
$status = 200;
if ($status < 200) $status = 500;
echo "";
?>
--EXPECT--
