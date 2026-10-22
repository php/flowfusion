--TEST--
Translated seed (inferredbugs-csharp: dotnet-standard-sdk/3)
--FILE--
<?php
$u = null;
$p = null;
$token = null;
$custom = ['x' => 1, 'y' => ['a' => 2]];
$endpoint = 'https://gateway.watsonplatform.net/language-translator/api';

$req = [
    'method' => 'POST',
    'url' => $endpoint . '/v2/translate',
    'headers' => ['Authorization' => $token !== null ? 'Bearer ' . $token : 'Basic ' . base64_encode($u . ':' . $p)],
    'body' => ['text' => ['hello'], 'model_id' => 'en-es'],
    'custom' => $custom,
];

$payload = json_encode($req['body'], JSON_UNESCAPED_UNICODE);
$parts = [];
$parts[] = "----phpboundary";
$parts[] = "Content-Disposition: form-data; name=\"base_model_id\"";
$parts[] = "";
$parts[] = "en-es";
$parts[] = "----phpboundary";
$parts[] = "Content-Disposition: form-data; name=\"name\"";
$parts[] = "";
$parts[] = "model";
$parts[] = "----phpboundary--";
$multipart = implode("\r\n", $parts);

$stream = fopen('php://temp', 'r+');
fwrite($stream, $multipart);
rewind($stream);
$data = stream_get_contents($stream);

$result = ['custom' => $req['custom'], 'payload' => $payload, 'multipart' => $data];
echo strlen($result['payload']) . "\n";
echo strlen($result['multipart']) . "\n";
echo count($result['custom'], COUNT_RECURSIVE) . "\n";
--EXPECT--
