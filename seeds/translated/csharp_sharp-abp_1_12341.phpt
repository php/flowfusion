--TEST--
Translated seed (inferredbugs-csharp: sharp-abp/1)
--FILE--
<?php
declare(strict_types=1);

$service = 'test-service';
$tag = '';
$cancellationToken = null;

$client = new stdClass();
$client->service = $service;
$client->tag = $tag;
$client->cancellationToken = $cancellationToken;

echo "";
?>
--EXPECT--
