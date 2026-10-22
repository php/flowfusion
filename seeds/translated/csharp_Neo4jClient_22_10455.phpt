--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/22)
--FILE--
<?php
$connected = false;
if (!$connected) {
    try {
        throw new RuntimeException('Not connected');
    } catch (RuntimeException $e) {
    }
}

$base = 'http://foo/db/data';
$connected = true;
$relationshipId = 456;
if ($connected) {
    $method = 'DELETE';
    $resource = '/relationship/' . $relationshipId;
    if ($base === 'http://foo/db/data' && $method === 'DELETE' && $resource === '/relationship/456') {
        $statusCode = 204;
    }
}

$deleteStatus = 404;
$deleteDescription = 'NOT FOUND';
if ($deleteStatus !== 204) {
    try {
        throw new Exception('Unable to delete the relationship. The response status was: ' . $deleteStatus . ' ' . $deleteDescription);
    } catch (Exception $e) {
    }
}
?>
--EXPECT--
