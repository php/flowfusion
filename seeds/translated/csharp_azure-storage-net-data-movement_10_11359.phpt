--TEST--
Translated seed (inferredbugs-csharp: azure-storage-net-data-movement/10)
--FILE--
<?php
$source = ['type' => 'local'];
$dest = ['type' => 'local', 'path' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_subdir_transfer_' . bin2hex(random_bytes(4))];
@mkdir($dest['path'], 0777, true);
$root = $dest['path'] . DIRECTORY_SEPARATOR . 'root';
@mkdir($root, 0777, true);

$entries = [
    ['isDir' => true, 'relativePath' => 'a', 'token' => 't1'],
    ['isDir' => false, 'relativePath' => 'a' . DIRECTORY_SEPARATOR . 'file.txt', 'token' => 't2'],
    ['isDir' => true, 'relativePath' => 'a' . DIRECTORY_SEPARATOR . 'b', 'token' => 't3'],
    ['isDir' => false, 'relativePath' => 'a' . DIRECTORY_SEPARATOR . 'b' . DIRECTORY_SEPARATOR . 'c.txt', 'token' => 't4'],
];

$continuation = null;
foreach ($entries as $entry) {
    $continuation = ['token' => $entry['token'], 'journal' => $continuation['journal'] ?? null, 'offset' => $continuation['offset'] ?? 0];
    $target = $root . DIRECTORY_SEPARATOR . $entry['relativePath'];
    if ($entry['isDir']) {
        @mkdir($target, 0777, true);
    } else {
        $parent = dirname($target);
        if (!is_dir($parent)) {
            @mkdir($parent, 0777, true);
        }
        file_put_contents($target, $continuation['token']);
    }
}

$check = [
    is_dir($root . DIRECTORY_SEPARATOR . 'a'),
    is_file($root . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'file.txt'),
    is_dir($root . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b'),
    is_file($root . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b' . DIRECTORY_SEPARATOR . 'c.txt'),
];
if (count(array_filter($check)) !== 4) {
    throw new RuntimeException('transfer failed');
}
?>
--EXPECT--
