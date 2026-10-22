--TEST--
Translated seed (inferredbugs-csharp: dotnet-standard-sdk/13)
--FILE--
<?php
$projectId = 'a/b c?d';
$collectionId = 'x/y z';
$documentId = 'doc#1';
$enrichmentId = 'enr,2';
$customerId = 'cust&3';
$version = '2020-12-01';
$base = 'https://api.us-south.discovery.watson.cloud.ibm.com';

$paths = [
    '/v2/projects/' . rawurlencode($projectId) . '/collections',
    '/v2/projects/' . rawurlencode($projectId) . '/collections/' . rawurlencode($collectionId),
    '/v2/projects/' . rawurlencode($projectId) . '/collections/' . rawurlencode($collectionId) . '/documents/' . rawurlencode($documentId),
    '/v2/projects/' . rawurlencode($projectId) . '/enrichments/' . rawurlencode($enrichmentId),
    '/v2/user_data',
];

$query = http_build_query([
    'version' => $version,
    'collection_ids' => implode(',', [$collectionId, 'c,d']),
    'customer_id' => $customerId,
], '', '&', PHP_QUERY_RFC3986);

$body = json_encode([
    'name' => 'n',
    'description' => 'd',
    'language' => 'en',
    'collection_ids' => [$collectionId, 'c,d'],
    'count' => 1,
    'offset' => 0,
    'highlight' => true,
    'spelling_suggestions' => false,
    'nested' => ['a' => ['b' => [1, 2, 3]]],
], JSON_UNESCAPED_SLASHES);

foreach ($paths as $p) {
    $u = $base . $p . '?' . $query;
    parse_url($u);
}
json_decode($body, true);
echo "";
?>
--EXPECT--
