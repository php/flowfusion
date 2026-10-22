--TEST--
Translated seed (inferredbugs-csharp: azure-storage-net-data-movement/3)
--FILE--
<?php
$items = [
    ['type' => 'dir', 'path' => 'a', 'token' => 't1'],
    ['type' => 'file', 'path' => 'a/x'],
    ['type' => 'dir', 'path' => 'a/b', 'token' => 't2'],
    ['type' => 'file', 'path' => 'a/b/y'],
];

$continuation = null;
foreach ($items as $entry) {
    if ($entry['type'] === 'dir') {
        $continuation = ['token' => $entry['token'], 'journal' => $continuation['journal'] ?? null, 'offset' => $continuation['offset'] ?? null];
        $state = $continuation;
    } else {
        $parent = dirname($entry['path']);
        if ($parent === '.') {
            $parent = '';
        }
        $state = ['parent' => $parent, 'token' => $continuation['token'] ?? null, 'journal' => $continuation['journal'] ?? null, 'offset' => $continuation['offset'] ?? null];
        $continuation = ['token' => $entry['path'], 'journal' => $continuation['journal'] ?? null, 'offset' => $continuation['offset'] ?? null];
    }
}

echo "ok\n";
?>
--EXPECT--
