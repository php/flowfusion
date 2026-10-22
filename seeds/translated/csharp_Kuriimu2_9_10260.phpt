--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/9)
--FILE--
<?php
$filteredPlugins = null;
$message = "Choose plugin";
$filterNote = "Filter note";

$showAllChecked = ($filteredPlugins === null);
$showAllEnabled = ($filteredPlugins !== null);

$rows = [
    [
        'type' => 'label',
        'text' => $message,
        'align' => 'center',
    ],
    [
        'type' => 'scrollable',
        'content' => [
            'type' => 'stack',
            'h' => 'stretch',
            'v' => 'stretch',
            'items' => [],
        ],
        'scaleHeight' => true,
    ],
    [
        'type' => 'checkbox',
        'text' => 'Show all plugins',
        'tooltip' => $filterNote,
        'checked' => $showAllChecked,
        'enabled' => $showAllEnabled,
    ],
    [
        'type' => 'buttonrow',
        'padding' => [0, 3],
        'spacing' => [3, 3],
        'cells' => [
            ['type' => 'spacer', 'scaleWidth' => true],
            ['type' => 'button', 'text' => 'Cancel'],
            ['type' => 'button', 'text' => 'Ok'],
        ],
    ],
];

echo json_encode([
    'title' => 'Choose plugin',
    'size' => [450, 700],
    'padding' => 3,
    'content' => ['type' => 'table', 'rows' => $rows],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), PHP_EOL;
?>
--EXPECT--
