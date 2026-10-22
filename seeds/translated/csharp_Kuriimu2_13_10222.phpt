--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/13)
--FILE--
<?php
$items = [];
for ($i = 0; $i < 2; $i++) {
    $items[] = ["text" => $i === 0 ? "Open with" : "Open", "cmd" => $i];
}
$menu = ["items" => $items];
$files = [];
for ($i = 0; $i < 3; $i++) {
    $files[] = ["Name" => "file$i", "Size" => (string)($i * 10)];
}
$search = "";
$searchClear = ["tooltip" => "Reset search", "size" => [22, -1]];
$toolstrip = ["bg" => "white", "items" => ["Save", "Save As"]];
$layout = [
    "spacing" => [3, 3],
    "rows" => [
        [
            "cells" => [
                ["search" => $search, "button" => $searchClear],
                ["tools" => ["Extract file(s)", "Replace file(s)", "Rename file", "Delete file"]],
            ],
        ],
        [
            "cells" => [
                ["folders" => []],
                ["files" => $files, "menu" => $menu],
            ],
        ],
    ],
];
echo "";
?>
--EXPECT--
