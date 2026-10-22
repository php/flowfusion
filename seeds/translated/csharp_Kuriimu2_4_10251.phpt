--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/4)
--FILE--
<?php
$okButtonCommand = new stdClass();
$cancelButtonCommand = new stdClass();

$title = "Choose plugin";
$size = [450, 700];
$padding = 3;

$pluginListPanel = [
    'HorizontalContentAlignment' => 'Stretch',
    'VerticalContentAlignment' => 'Stretch',
];

$okButton = [
    'Text' => 'Ok',
    'Command' => $okButtonCommand,
];

$content = [
    'Orientation' => 'Vertical',
    'FixedPanel' => 'Panel2',
    'Panel1' => [
        'Scrollable' => true,
        'Content' => $pluginListPanel,
    ],
    'Panel2' => [
        'Padding' => 3,
        'Spacing' => [3, 3],
        'Rows' => [
            [
                'Cells' => [
                    ['ScaleWidth' => true],
                    ['Text' => 'Cancel', 'Command' => $cancelButtonCommand],
                    $okButton,
                ],
            ],
        ],
    ],
];

echo "";
?>
--EXPECT--
