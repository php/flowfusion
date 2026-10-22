--TEST--
Translated seed (inferredbugs-java: aXMLRPC/2)
--FILE--
<?php

$url = 'http://localhost/xmlrpc'; // Replace with actual URL
$userAgent = 'aXMLRPC';
$flags = 0x01; // Strict flag

$httpParameters = [
    'Content-Type' => 'text/xml',
    'User-Agent' => $userAgent,
];

$method = 'testMethod';
$params = ['param1', 'param2'];

$callXml = '<?xml version="1.0"?><methodCall><methodName>' . $method . '</methodName><params>';
foreach ($params as $param) {
    $callXml .= '<param><value>' . htmlspecialchars($param) . '</value></param>';
}
$callXml .= '</params></methodCall>';

$context = stream_context_create([
    'http' => [
        'header' => http_build_query($httpParameters, '', "\r\n"),
        'method' => 'POST',
        'content' => $callXml,
    ],
]);

$response = file_get_contents($url, false, $context);
if ($response === false) {
    throw new RuntimeException('HTTP request failed');
}

if ($flags & 0x01 && strpos($http_response_header[0], '200') === false) {
    throw new RuntimeException('Failed request, response code not 200');
}

$xml = simplexml_load_string($response);
--EXPECT--
