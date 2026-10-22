--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/12)
--FILE--
<?php
$tabview = ["Data" => "tabview1", "Text" => "", "Width" => 50, "Height" => -1, "X" => 0, "Y" => 0, "MaxTabTextWidth" => 30, "Style" => ["ShowBorder" => true, "ShowTopLine" => true, "TabsOnBottom" => false], "Tabs" => []];

$tab1 = ["Text" => "Tab1", "View" => ["Width" => -1, "Height" => -1, "Children" => []]];
$tab1["View"]["Children"][] = ["Data" => "label1", "Text" => "Heya", "Width" => 4, "Height" => 1, "X" => "center", "Y" => ["percent" => 25]];
$tab1["View"]["Children"][] = ["Data" => "button1", "Text" => "Press Me!", "Width" => 15, "Height" => 1, "X" => 5, "Y" => 3, "IsDefault" => true];
$tab1["View"]["Children"][] = ["Data" => "checkbox1", "Text" => "Check me", "Width" => 4, "Height" => 1, "X" => 0, "Y" => 0, "Checked" => true];
$tabview["Tabs"][] = $tab1;

$tab2 = ["Text" => "Tab2", "View" => ["Width" => -1, "Height" => -1, "Children" => []]];
$tab2["View"]["Children"][] = ["Data" => "label2", "Text" => "Label 1", "Width" => 7, "Height" => 1, "X" => 10, "Y" => 3];
$tab2["View"]["Children"][] = ["Data" => "label3", "Text" => "Label 2", "Width" => 7, "Height" => 1, "X" => 0, "Y" => 0];
$tabview["Tabs"][] = $tab2;

echo "";
?>
--EXPECT--
