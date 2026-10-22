--TEST--
Translated seed (inferredbugs-csharp: RestSharp/3)
--FILE--
<?php
$handlers = [];
$acceptTypes = [];

$addHandler = function ($contentType, $handler) use (&$handlers, &$acceptTypes) {
    $handlers[$contentType] = $handler;
    if ($contentType !== '*') {
        $acceptTypes[] = $contentType;
    }
};

$addHandler('application/json', 'json');
$addHandler('application/xml', 'xml');
$addHandler('text/json', 'json');
$addHandler('text/x-json', 'json');
$addHandler('text/javascript', 'json');
$addHandler('text/xml', 'xml');
$addHandler('*', 'xml');

$baseUrl = 'http://example.com/';
if (substr($baseUrl, -1) === '/') {
    $baseUrl = substr($baseUrl, 0, -1);
}

$request = [
    'Resource' => '/items/{id};charset=utf-8',
    'Parameters' => [
        ['Type' => 'UrlSegment', 'Name' => 'id', 'Value' => 42],
        ['Type' => 'HttpHeader', 'Name' => 'X-Test', 'Value' => 'a'],
        ['Type' => 'Cookie', 'Name' => 'sid', 'Value' => 'b'],
        ['Type' => 'GetOrPost', 'Name' => 'q', 'Value' => 'c'],
        ['Type' => 'RequestBody', 'Name' => 'application/json', 'Value' => '{"ok":true}'],
    ],
    'Files' => [
        ['ContentType' => 'text/plain', 'Data' => 'abc', 'FileName' => 'f.txt']
    ],
    'Timeout' => 0,
    'Credentials' => null,
    'RootElement' => null,
    'DateFormat' => null,
    'XmlNamespace' => null,
];

$assembled = $request['Resource'];
foreach ($request['Parameters'] as $p) {
    if ($p['Type'] === 'UrlSegment') {
        $assembled = str_replace('{' . $p['Name'] . '}', (string)$p['Value'], $assembled);
    }
}
if ($assembled !== '' && $assembled[0] === '/') {
    $assembled = substr($assembled, 1);
}
$uri = $baseUrl . '/' . $assembled;

$contentType = 'application/xml; charset=utf-8';
$semicolonIndex = strpos($contentType, ';');
if ($semicolonIndex !== false) {
    $contentType = substr($contentType, 0, $semicolonIndex);
}
$handler = $handlers[$contentType] ?? ($handlers['*'] ?? null);

echo $uri, "\n";
echo $handler, "\n";
echo count($acceptTypes), "\n";
?>
--EXPECT--
