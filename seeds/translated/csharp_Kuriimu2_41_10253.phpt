--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/41)
--FILE--
<?php
$items = [];
$items[] = ['name' => 'Open', 'img' => 'open'];
$items[] = ['name' => 'OpenWith', 'img' => 'openwith'];
$items[] = ['name' => 'Save', 'img' => 'save', 'shortcut' => 'Ctrl+S'];
$items[] = ['name' => 'SaveAs', 'img' => 'saveas', 'shortcut' => 'Ctrl+Shift+S'];
$items[] = ['name' => 'Extract', 'img' => 'folderexport'];
$items[] = ['name' => 'Replace', 'img' => 'folderimport'];
$items[] = ['name' => 'Rename', 'img' => 'rename'];
$items[] = ['name' => 'Delete', 'img' => 'delete'];
$items[] = ['name' => 'Add', 'img' => 'add'];
$items[] = ['name' => 'Extract', 'img' => 'fileexport'];
$items[] = ['name' => 'Replace', 'img' => 'fileimport'];
$items[] = ['name' => 'Rename', 'img' => 'rename'];
$items[] = ['name' => 'Delete', 'img' => 'delete'];

$folders = [];
$files = [];
for ($i = 0; $i < 3; $i++) {
    $folders[] = ['text' => "folder_$i", 'children' => []];
    $files[] = ['name' => "file_$i", 'size' => (string)(($i + 1) * 1024)];
}

$menu = [
    'folder' => array_slice($items, 4, 5),
    'file' => [
        $items[0],
        $items[1],
        null,
        $items[9],
        $items[10],
        $items[11],
        $items[12],
    ],
];

$layout = [
    'search' => ['size' => [268, -1], 'clear' => ['tooltip' => 'ClearSearch']],
    'toolbar' => ['padding' => 3, 'items' => [$items[2], $items[3]]],
    'main' => [
        'spacing' => [3, 3],
        'rows' => [
            [
                'searchbox' => true,
                'filetools' => true,
            ],
            [
                'folders' => $folders,
                'files' => $files,
            ],
        ],
    ],
];

echo "";
?>
--EXPECT--
