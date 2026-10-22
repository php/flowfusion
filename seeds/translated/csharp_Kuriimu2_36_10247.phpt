--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/36)
--FILE--
<?php
$keys = [
    "Open","OpenWith","Save","SaveAs","ClearSearch","CancelOperation",
    "Extract","Replace","Rename","Delete","Add","ExtractFiles","ReplaceFiles",
    "RenameFile","DeleteFile","FileName","FileSize"
];

$commands = [];
foreach ($keys as $k) {
    $commands[$k] = ["MenuText" => $k, "Image" => strrev($k)];
}
$commands["Save"]["Shortcut"] = "Ctrl+S";
$commands["SaveAs"]["Shortcut"] = "Ctrl+Shift+S";

$folders = [];
$files = new ArrayObject();

$folders[] = ["Text" => "root", "Children" => []];
$folders[0]["Children"][] = ["Text" => "sub", "Children" => []];

$files[] = (object)["Name" => "a.txt", "Size" => 1];
$files[] = (object)["Name" => "b.bin", "Size" => 2];

$contextMenus = [
    "folder" => [
        $commands["Extract"],
        $commands["Replace"],
        $commands["Rename"],
        $commands["Add"],
        $commands["Delete"]
    ],
    "file" => [
        $commands["Open"],
        ["Text" => $commands["OpenWith"]["MenuText"], "Command" => $commands["OpenWith"], "Image" => $commands["OpenWith"]["Image"]],
        null,
        $commands["Extract"],
        $commands["Replace"],
        $commands["Rename"],
        $commands["Delete"]
    ]
];

$toolstrip = [
    $commands["Save"],
    $commands["SaveAs"]
];

$search = "   ";
$search = trim($search);
$search .= "x";
$search = str_replace("x", "", $search);

foreach ($files as $f) {
    $f->Name = $f->Name . "";
    $f->Size = (int)$f->Size;
}

echo "";
?>
--EXPECT--
