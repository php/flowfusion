--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/13)
--FILE--
<?php
$w = 0;
$h = 0;
$x = 0;
$y = 0;
$title = "Welcome to Demo";

$tabview = [
    'data' => 'tabview1',
    'text' => '',
    'width' => 50,
    'height' => null,
    'x' => 0,
    'y' => 0,
    'maxTabTextWidth' => 30,
    'style' => ['showBorder' => true, 'showTopLine' => true, 'tabsOnBottom' => false],
    'tabs' => []
];

$tabview['tabs'][] = [
    'title' => 'Tab1',
    'view' => [
        'width' => null,
        'height' => null,
        'children' => [
            ['type' => 'label', 'data' => 'label1', 'text' => 'Heya', 'width' => null, 'height' => 1, 'x' => 0, 'y' => '50%', 'align' => 'center'],
            ['type' => 'button', 'data' => 'button1', 'text' => 'Press Me!', 'width' => 15, 'height' => 1, 'x' => 5, 'y' => 3, 'align' => 'center', 'default' => true],
            ['type' => 'checkbox', 'data' => 'checkbox1', 'text' => 'Check me', 'width' => 4, 'height' => 1, 'x' => 0, 'y' => 0, 'align' => 'left', 'checked' => true],
        ]
    ]
];

$tabview['tabs'][] = [
    'title' => 'Tab2',
    'view' => [
        'width' => null,
        'height' => null,
        'children' => [
            ['type' => 'label', 'data' => 'label2', 'text' => 'Label 1', 'width' => 7, 'height' => 1, 'x' => 10, 'y' => 3, 'align' => 'left'],
            ['type' => 'label', 'data' => 'label3', 'text' => 'Label 2', 'width' => 7, 'height' => 1, 'x' => 0, 'y' => 0, 'align' => 'left'],
        ]
    ]
];

echo "";
?>
--EXPECT--
