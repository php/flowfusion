--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/68)
--FILE--
<?php
$traceId = bin2hex(random_bytes(16));
$parentId = bin2hex(random_bytes(8));
$transaction = ['name' => 'TestTransaction', 'type' => 'UnitTest', 'traceId' => $traceId, 'parentId' => null, 'id' => $parentId];
$invalid = [
    ['', ''],
    [null, null],
    ['aaa', 'bbb'],
    ['null', '5ec5de4fdae36f4c'],
    ['005a66g3c2fb9591a0e53d322df6c3e2', 'null'],
    ['00000000000000000000000000000000', '0000000000000000'],
    ['005a66g3c2fb9591a0e53d322df6c3e2', '5ec5de4fdae36f4c'],
    ['005a66a3c2fb9591a0e53d322df6c3e2', '5ec5de4fdaei6f4c'],
    ['005a6663c2fb9591a0e53d322d6c3e2', '5ec5de4fdae36f4c'],
    ['005a6663c2fb9591a0e53d322df6c3e2', '5ec5defdae36f4c'],
    ['005a6663c2fb95291a0e53d322df6c3e2', '5ec5de4fdae36f4c'],
    ['005a6663c2fb9591a0e53d322df6c3e2', '56ec5de4fdae36f4c'],
];
foreach ($invalid as [$t, $p]) {
    if ($t !== null && $p !== null && preg_match('/^[0-9a-f]{32}$/', $t) && preg_match('/^[0-9a-f]{16}$/', $p)) {
        continue;
    }
    $newTraceId = bin2hex(random_bytes(16));
    $newParentId = null;
    if ($newTraceId === $t || $newParentId === $p) {
        $newTraceId = bin2hex(random_bytes(16));
        $newParentId = null;
    }
}
echo "";
?>
--EXPECT--
