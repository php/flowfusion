--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/5)
--FILE--
<?php
$items = [];
$items[] = ['cmd' => 'save', 'img' => 'MenuSaveResource'];
$items[] = ['cmd' => 'saveAs', 'img' => 'MenuSaveAsResource'];
array_splice($items, 2, 0, [['splitter' => true]]);
$items[] = ['cmd' => 'export', 'img' => 'MenuExportResource'];
$items[] = ['cmd' => 'import', 'img' => 'MenuImportResource'];

$layout = [
    'image' => [
        'spacing' => 3,
        'orientation' => 'vertical',
        'items' => [
            ['expand' => true, 'align' => 'stretch', 'view' => 'imageViewEx'],
            [
                'spacing' => [3, 3],
                'rows' => [
                    ['width:', null, 'height:', null, ['scaleWidth' => true]],
                    ['format:', [], 'palette:', [], ['scaleWidth' => true]],
                ],
            ],
        ],
    ],
    'list' => [
        'orientation' => 'vertical',
        'items' => [
            ['expand' => true, 'control' => 'images'],
            ['expand' => true, 'control' => 'imagePalette'],
        ],
    ],
];

$main = [
    'spacing' => [3, 3],
    'rows' => [
        [
            [
                'scaleWidth' => true,
                'layout' => $layout['image'],
            ],
            $layout['list'],
        ],
    ],
];

$content = [
    'rows' => [
        [
            'panel' => [
                'content' => $items,
                'size' => [-1, (int)6 + 6],
            ],
        ],
        $main,
    ],
];

echo json_encode($content, JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
