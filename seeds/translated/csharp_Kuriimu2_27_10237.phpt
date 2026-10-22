--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/27)
--FILE--
<?php
$folderContext = [];
$folderContext[] = "extract";
$folderContext[] = "replace";
$folderContext[] = "rename";
$folderContext[] = "add";
$folderContext[] = "delete";

$files = new ArrayObject();
$files->append(["Name" => "a", "Size" => 1]);
$files->append(["Name" => "b", "Size" => 2]);

$search = "";
$searchClear = true;
$cancel = false;

$openWith = ["Text" => "Open with", "Image" => "openwith"];
$fileContext = [];
$fileContext[] = "open";
$fileContext[] = $openWith;
$fileContext[] = null;
$fileContext[] = "extract";
$fileContext[] = "replace";
$fileContext[] = "rename";
$fileContext[] = "delete";

$toolstrip = ["save", "saveas", "extract", "replace", "rename", "delete"];

$table = [
    ["search", "filetools"],
    ["folders", "files"]
];

$content = [
    "archiveToolStrip" => ["padding" => 3, "items" => ["save", "saveas"]],
    "mainContent" => ["spacing" => [3, 3], "rows" => $table]
];

echo "";
?>
--EXPECT--
