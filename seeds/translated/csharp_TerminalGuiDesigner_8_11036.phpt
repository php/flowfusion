--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/8)
--FILE--
<?php
$tabview = ["data" => "tabview1", "text" => "", "width" => 50, "height" => 5, "x" => 0, "y" => 0, "tabs" => []];
$tabview["tabs"][] = ["text" => "Tab1"];
$tabview["tabs"][] = ["text" => "Tab2"];

$lblTitle = ["data" => "lblTitle", "text" => "Welcome to Demo", "width" => 15, "height" => 1, "x" => 22, "y" => 4];
$label2 = ["data" => "label2", "text" => "Heya", "width" => 4, "height" => 1, "x" => 22, "y" => $lblTitle["y"] + $lblTitle["height"] + 1];

$table = new SplFixedArray(4);
$table[0] = "Column 0";
$table[1] = "Column 1";
$table[2] = "Column 2";
$table[3] = "Column 3";

$tableview = ["data" => "tableview1", "text" => "", "width" => null, "height" => 5, "x" => 0, "y" => 9, "style" => [
    "AlwaysShowHeaders" => false,
    "ExpandLastColumn" => true,
    "InvertSelectedCellFirstCharacter" => false,
    "ShowHorizontalHeaderOverline" => true,
    "ShowHorizontalHeaderUnderline" => false,
    "ShowVerticalCellLines" => true,
    "ShowVerticalHeaderLines" => true,
], "table" => $table];

$window = ["children" => [$tabview, $lblTitle, $label2, $tableview]];
echo "";
?>
--EXPECT--
