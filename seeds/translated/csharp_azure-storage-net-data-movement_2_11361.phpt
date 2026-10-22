--TEST--
Translated seed (inferredbugs-csharp: azure-storage-net-data-movement/2)
--FILE--
<?php
$items = [
    ['type' => 'dir', 'path' => 'a', 'token' => 't1'],
    ['type' => 'file', 'path' => 'a/x.txt', 'token' => 't2'],
    ['type' => 'dir', 'path' => 'a/b', 'token' => 't3'],
    ['type' => 'file', 'path' => 'a/b/y.txt', 'token' => 't4'],
];

$continuation = ['journal' => null, 'offset' => 0, 'token' => null];
$seen = [];

foreach ($items as $entry) {
    if ($entry['type'] === 'dir') {
        $continuation = ['journal' => $continuation['journal'], 'offset' => $continuation['offset'], 'token' => $entry['token']];
        $seen[] = $entry['path'] . ':' . $continuation['token'];
    } else {
        $parent = dirname($entry['path']);
        if ($parent === '.') {
            $parent = '';
        }
        $continuation = ['journal' => $continuation['journal'], 'offset' => $continuation['offset'], 'token' => $entry['token']];
        $seen[] = $parent . ':' . $continuation['token'];
    }
}

echo implode("\n", $seen), "\n";
?>
--EXPECT--
