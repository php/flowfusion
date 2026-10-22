--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/9)
--FILE--
<?php
$tabview1 = ["Data" => "tabview1", "Text" => "", "Width" => 50, "Height" => null, "X" => 0, "Y" => 0, "MaxTabTextWidth" => 30, "Style" => ["ShowBorder" => true, "ShowTopLine" => true, "TabsOnBottom" => false], "Tabs" => []];

$tabview1["Tabs"][] = ["Text" => "Tab1", "View" => ["Width" => null, "Height" => null, "Children" => []]];
$tabview1["Tabs"][0]["View"]["Children"][] = ["Data" => "label1", "Text" => "Heya", "Width" => 4, "Height" => 1, "X" => 0, "Y" => 0];

$tabview1["Tabs"][] = ["Text" => "Tab2", "View" => ["Width" => null, "Height" => null, "Children" => []]];
$tabview1["Tabs"][1]["View"]["Children"][] = ["Data" => "label2", "Text" => "Heya", "Width" => 4, "Height" => 1, "X" => 0, "Y" => 0];

$window = ["Children" => []];
$window["Children"][] = $tabview1;
?>
--EXPECT--
