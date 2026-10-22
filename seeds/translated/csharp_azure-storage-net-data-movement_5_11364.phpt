--TEST--
Translated seed (inferredbugs-csharp: azure-storage-net-data-movement/5)
--FILE--
<?php
$items = [
    ['dir', 'a', 't1'],
    ['file', 'a/x', 't2'],
    ['file', 'a/y', 't3'],
    ['dir', 'b', 't4'],
    ['file', 'b/z', 't5'],
];

$token = null;
$state = [];
foreach ($items as $entry) {
    [$kind, $path, $nextToken] = $entry;
    $state[] = $token;
    $token = ['token' => $nextToken, 'journal' => $token['journal'] ?? null, 'offset' => $token['offset'] ?? 0];
    if ($kind === 'dir') {
        $dirs[] = $path;
    } else {
        $parts = explode('/', $path);
        array_pop($parts);
        $parent = implode('/', $parts);
        if ($parent !== '' && !isset($created[$parent])) {
            $created[$parent] = true;
        }
        $files[] = $path;
    }
}
echo "";
?>
--EXPECT--
