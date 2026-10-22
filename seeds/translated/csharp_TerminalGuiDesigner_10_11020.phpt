--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/10)
--FILE--
<?php
$tabview1 = ["Data" => "tabview1", "Text" => "", "Width" => 50, "Height" => null, "X" => 0, "Y" => 0, "MaxTabTextWidth" => 30, "Style" => ["ShowBorder" => true, "ShowTopLine" => true, "TabsOnBottom" => false], "Tabs" => []];

$tabview1Tab1 = ["Text" => "Tab1", "View" => ["Width" => null, "Height" => null, "Children" => []]];
$tabview1Tab1["View"]["Width"] = "Fill";
$tabview1Tab1Tab1View = &$tabview1Tab1["View"];
$tabview1Tab1Tab1View["Height"] = "Fill";
$label1 = ["Data" => "label1", "Text" => "Heya", "Width" => 4, "Height" => 1, "X" => 0, "Y" => 0];
$button1 = ["Data" => "button1", "Text" => "Press Me!", "Width" => 15, "Height" => 1, "X" => 5, "Y" => 3, "IsDefault" => true];
$tabview1Tab1["View"]["Children"][] = $label1;
$tabview1Tab1["View"]["Children"][] = $button1;
$tabview1["Tabs"][] = $tabview1Tab1;

$tabview1Tab2 = ["Text" => "Tab2", "View" => ["Width" => null, "Height" => null, "Children" => []]];
$tabview1Tab2["View"]["Width"] = "Fill";
$tabview1Tab2["View"]["Height"] = "Fill";
$label2 = ["Data" => "label2", "Text" => "Heya", "Width" => 4, "Height" => 1, "X" => 0, "Y" => 0];
$tabview1Tab2["View"]["Children"][] = $label2;
$tabview1["Tabs"][] = $tabview1Tab2;

echo count($tabview1["Tabs"]) . PHP_EOL;
?>
--EXPECT--
