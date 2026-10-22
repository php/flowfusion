--TEST--
Translated seed (inferredbugs-csharp: python-language-server/2)
--FILE--
<?php
$settings = ["analysis" => ["openFilesOnly" => true, "errors" => [], "warnings" => [], "information" => [], "disabled" => []], "completion" => ["showAdvancedMembers" => false, "addBrackets" => false]];
$current = $settings;
$current["analysis"]["errors"] = ["E1", "E2"];
$current["analysis"]["warnings"] = ["W1"];
$current["analysis"]["information"] = ["I1"];
$current["analysis"]["disabled"] = ["D1"];
$merged = ["completion" => ["showAdvancedMembers" => true, "addBrackets" => true], "analysis" => ["openFilesOnly" => $current["analysis"]["openFilesOnly"], "errors" => $current["analysis"]["errors"], "warnings" => $current["analysis"]["warnings"], "information" => $current["analysis"]["information"], "disabled" => $current["analysis"]["disabled"]]];
$merged["analysis"]["errors"][] = "E3";
$merged["analysis"]["warnings"][] = "W2";
$merged["analysis"]["information"][] = "I2";
$merged["analysis"]["disabled"][] = "D2";
$json = json_encode($merged);
$decoded = json_decode($json, true);
$decoded["completion"]["showAdvancedMembers"] = !$decoded["completion"]["showAdvancedMembers"];
$decoded["completion"]["addBrackets"] = !$decoded["completion"]["addBrackets"];
$decoded["analysis"]["openFilesOnly"] = !$decoded["analysis"]["openFilesOnly"];
print_r($decoded);
?>
--EXPECT--
