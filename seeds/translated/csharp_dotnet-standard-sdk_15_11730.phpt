--TEST--
Translated seed (inferredbugs-csharp: dotnet-standard-sdk/15)
--FILE--
<?php
$projectId = 'a/b c?d#e';
$collectionId = 'x/y z';
$documentId = 'doc/1';
$version = '2021-05-13';

$u = rawurlencode($projectId);
$c = rawurlencode($collectionId);
$d = rawurlencode($documentId);

$q = http_build_query([
    'version' => $version,
    'collection_ids' => implode(',', ['c1', 'c2']),
    'prefix' => 'Ho',
    'count' => 0,
    'offset' => 10000,
], '', '&', PHP_QUERY_RFC3986);

$body = json_encode([
    'name' => 'n',
    'description' => '',
    'language' => 'en',
    'collection_ids' => ['c1', 'c2'],
    'count' => 0,
    'offset' => 10000,
    'highlight' => false,
    'spelling_suggestions' => true,
    'enrichments' => [],
], JSON_UNESCAPED_SLASHES);

$multipart = [
    'file' => "line1\r\nline2\x00end",
    'metadata' => '{"Creator":"Johnny Appleseed","Subject":"Apples"}',
];

echo $u, "\n", $c, "\n", $d, "\n", $q, "\n", $body, "\n";
var_export($multipart);
echo "\n";
?>
--EXPECT--
