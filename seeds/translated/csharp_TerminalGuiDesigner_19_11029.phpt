--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/19)
--FILE--
<?php
$table = new SplFixedArray(14);
$table[0] = 'Name';
$table[1] = ' ';
$table[2] = '  ';
for ($i = 0; $i <= 9; $i++) $table[$i + 3] = (string)$i;
$table[13] = 'A';
$labels = [];
for ($i = 0; $i < $table->count(); $i++) $labels[] = $table[$i];
$help = 'Help:';
$layout = [
    'title' => 'Color Schemes (Ctrl+Q to exit)',
    'table' => $labels,
    'help' => $help,
    'width' => 0,
    'height' => 0,
    'x' => 0,
    'y' => 0,
];
echo '';
?>
--EXPECT--
