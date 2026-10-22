--TEST--
Translated seed (inferredbugs-csharp: azure-storage-net-data-movement/4)
--FILE--
<?php
$items = [
    ['dir', 'a', 't1'],
    ['file', 'a/x', 't2'],
    ['dir', 'b', 't3'],
    ['file', 'b/y', 't4'],
];
$token = null;
$seen = [];
foreach ($items as $entry) {
    [$kind, $path, $next] = $entry;
    if ($kind === 'dir') {
        $token = ['continuation' => $next, 'journal' => $token['journal'] ?? null, 'offset' => $token['offset'] ?? null];
        $seen[] = $path . ':' . $token['continuation'];
    } else {
        $parent = dirname($path);
        if ($parent === '.') {
            $parent = '';
        }
        $token = ['continuation' => $next, 'journal' => $token['journal'] ?? null, 'offset' => $token['offset'] ?? null];
        $seen[] = $parent . '/' . $token['continuation'];
    }
}
echo implode("\n", $seen), "\n";
?>
--EXPECT--
