--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/15)
--FILE--
<?php
$graphview1 = [
    'Data' => 'graphview1',
    'Text' => '',
    'Width' => 60,
    'Height' => 10,
    'X' => 4,
    'Y' => 1,
    'TextAlignment' => 'Left',
    'GraphColor' => ['Black', 'White'],
    'ScrollOffset' => [0.0, 0.0],
    'MarginLeft' => 2,
    'MarginBottom' => 2,
    'AxisX' => ['Visible' => true, 'Increment' => 1.0, 'ShowLabelsEvery' => 5, 'Minimum' => null],
    'AxisY' => ['Visible' => true, 'Increment' => 0.1, 'ShowLabelsEvery' => 1, 'Minimum' => null],
];

$tableview1Table = new SplFixedArray(4);
$tableview1Table[0] = 'Column 0';
$tableview1Table[1] = 'Column 1';
$tableview1Table[2] = 'Column 2';
$tableview1Table[3] = 'Column 3';

$tableview1 = [
    'Data' => 'tableview1',
    'Text' => '',
    'Width' => 50,
    'Height' => 5,
    'X' => 6,
    'Y' => 12,
    'TextAlignment' => 'Left',
    'Style' => [
        'AlwaysShowHeaders' => false,
        'ExpandLastColumn' => true,
        'InvertSelectedCellFirstCharacter' => false,
        'ShowHorizontalHeaderOverline' => true,
        'ShowHorizontalHeaderUnderline' => true,
        'ShowVerticalCellLines' => true,
        'ShowVerticalHeaderLines' => true,
    ],
    'Table' => $tableview1Table,
];

echo "";
?>
--EXPECT--
