--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/69)
--FILE--
<?php
declare(strict_types=1);

$traceId = bin2hex(random_bytes(16));
$parentId = bin2hex(random_bytes(8));
$tx = ['id' => bin2hex(random_bytes(8)), 'traceId' => $traceId, 'parentId' => null, 'spans' => []];
$span1 = ['id' => bin2hex(random_bytes(8)), 'parentId' => $tx['id'], 'traceId' => $traceId, 'tags' => ['foo' => 'bar'], 'subspans' => []];
$span2 = ['id' => bin2hex(random_bytes(8)), 'parentId' => $span1['id'], 'traceId' => $traceId, 'tags' => [], 'error' => null];
$span2['tags']['foo'] = 'bar';
$span2['error'] = ['type' => 'Exception', 'message' => 'test exception'];
$span1['subspans'][] = $span2;
$tx['spans'][] = $span1;
$continued = ['id' => bin2hex(random_bytes(8)), 'traceId' => $traceId, 'parentId' => $parentId];
$invalid = ['traceId' => '005a66g3c2fb9591a0e53d322df6c3e2', 'parentId' => '5ec5de4fdae36f4c'];
if ($continued['traceId'] !== $traceId || $continued['parentId'] !== $parentId) {
    exit(1);
}
if ($invalid['traceId'] === $traceId || $invalid['parentId'] !== '5ec5de4fdae36f4c') {
    exit(1);
}
echo '';
?>
--EXPECT--
