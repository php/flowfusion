--TEST--
Translated seed (inferredbugs-csharp: azure-storage-net-data-movement/8)
--FILE--
<?php
$items = [
    ['dir', 'a', 'tok1'],
    ['file', 'a/b.txt', 'tok2'],
    ['file', 'a/c.txt', 'tok3'],
    ['dir', 'd', 'tok4'],
];

$continuation = null;
foreach ($items as $entry) {
    if ($entry[0] === 'dir') {
        $continuation = ['token' => $entry[2], 'journal' => $continuation['journal'] ?? null, 'offset' => $continuation['offset'] ?? 0];
    } else {
        $path = $entry[1];
        $parent = dirname($path);
        if ($parent !== '.' && !is_dir($parent)) {
            mkdir($parent, 0777, true);
        }
        $continuation = ['token' => $entry[2], 'journal' => $continuation['journal'] ?? null, 'offset' => $continuation['offset'] ?? 0];
        file_put_contents($path, "");
    }
}

echo "ok\n";
--EXPECT--
