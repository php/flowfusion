--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/8)
--FILE--
<?php
$rows = [];
$rows[] = ['scaleHeight' => true, 'cells' => [['scrollable' => ['content' => ['horizontal' => 'stretch', 'vertical' => 'stretch']]]]];
$rows[] = ['padding' => [0, 3], 'spacing' => [3, 3], 'rows' => [['cells' => [['scaleWidth' => true], ['text' => 'Cancel', 'command' => null], ['text' => 'Ok', 'command' => null]]]]];

$dialog = [
    'title' => 'Choose plugin',
    'size' => [450, 700],
    'padding' => [3],
    'content' => ['rows' => $rows]
];

echo '';
?>
--EXPECT--
