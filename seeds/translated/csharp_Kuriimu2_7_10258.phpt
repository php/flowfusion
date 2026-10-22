--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/7)
--FILE--
<?php
$commands = [
    ['MenuText' => 'Open', 'Shortcut' => 'Ctrl+O'],
    ['MenuText' => 'Open with Plugin', 'Shortcut' => 'Ctrl+Shift+O'],
    ['MenuText' => 'Save All', 'Shortcut' => 'Ctrl+S', 'Image' => 'MenuSaveResource'],
    ['MenuText' => 'Text Sequence Searcher'],
    ['MenuText' => 'Batch Extractor'],
    ['MenuText' => 'Batch Injector'],
    ['MenuText' => 'Hashes'],
    ['MenuText' => 'Encrypt'],
    ['MenuText' => 'Decrypt'],
    ['MenuText' => 'Decompress'],
    ['MenuText' => 'Compress'],
    ['MenuText' => 'Raw Image Viewer'],
    ['MenuText' => 'Image Trascoder'],
    ['MenuText' => 'About...'],
];

$menu = [
    'Items' => [
        ['Text' => 'File', 'Items' => [$commands[0], $commands[1], null, $commands[2]]],
        ['Text' => 'Tools', 'Items' => [$commands[4], $commands[5], $commands[3]]],
        $commands[6],
        ['Text' => 'Ciphers', 'Items' => [$commands[7], $commands[8]]],
        ['Text' => 'Compressions', 'Items' => [$commands[9], $commands[10]]],
        $commands[11],
    ],
    'AboutItem' => $commands[13],
];

$layout = [
    'Spacing' => [3, 3],
    'Rows' => [
        [
            'Cells' => [
                ['Control' => 'ProgressBarEx', 'ScaleWidth' => true],
                ['Control' => 'Label', 'ScaleWidth' => true],
            ],
        ],
    ],
];

$form = [
    'Title' => 'Kuriimu2',
    'ClientSize' => [1116, 643],
    'Padding' => 3,
    'Icon' => 'Kuriimu2.EtoForms.Images.kuriimu2winforms.ico',
    'Menu' => $menu,
    'Content' => [
        'AllowDrop' => true,
        'Spacing' => [3, 3],
        'Rows' => [
            ['Control' => 'TabControl', 'ScaleHeight' => true],
            $layout,
        ],
    ],
];

echo json_encode($form, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), PHP_EOL;
--EXPECT--
