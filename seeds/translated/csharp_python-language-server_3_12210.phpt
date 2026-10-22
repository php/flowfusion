--TEST--
Translated seed (inferredbugs-csharp: python-language-server/3)
--FILE--
<?php
$settings = ["completion" => ["showAdvancedMembers" => false, "addBrackets" => false], "analysis" => ["openFilesOnly" => false, "errors" => [], "warnings" => [], "information" => [], "disabled" => []]];
$current = $settings;
$current["analysis"]["errors"] = ["E1"];
$current["analysis"]["warnings"] = ["W1"];
$current["analysis"]["information"] = ["I1"];
$current["analysis"]["disabled"] = ["D1"];
$settings["completion"]["showAdvancedMembers"] = true;
$settings["completion"]["addBrackets"] = true;
$settings["analysis"]["openFilesOnly"] = $current["analysis"]["openFilesOnly"];
$settings["analysis"]["errors"] = $current["analysis"]["errors"];
$settings["analysis"]["warnings"] = $current["analysis"]["warnings"];
$settings["analysis"]["information"] = $current["analysis"]["information"];
$settings["analysis"]["disabled"] = $current["analysis"]["disabled"];
$doc = ["uri" => "file:///test.py", "text" => "x=1\nx", "languageId" => "python"];
$changes = [["text" => "x=2\nx"]];
$doc["text"] = $changes[0]["text"];
$pos = ["line" => 1, "character" => 1];
$completion = ["textDocument" => ["uri" => $doc["uri"]], "position" => $pos];
$hover = ["textDocument" => ["uri" => $doc["uri"]], "position" => $pos];
$signature = ["textDocument" => $doc["uri"], "position" => $pos];
$references = ["textDocument" => $doc["uri"], "position" => $pos, "context" => ["includeDeclaration" => true, "_includeValues" => true]];
$builtin = ["int", "str", "list"];
echo "";
?>
--EXPECT--
