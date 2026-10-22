--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/26)
--FILE--
<?php
$items = [
    ["type" => "dir", "name" => "php", "children" => [
        ["type" => "dir", "name" => "js", "children" => [
            ["type" => "file", "name" => "a.txt", "data" => str_repeat("A", 3)],
            ["type" => "file", "name" => "caffè.jpg", "data" => str_repeat("B", 0)],
        ]],
        ["type" => "file", "name" => "README.md", "data" => "# readme\n"],
    ]],
];

$path = "/public_html/temp/php/js/";
$parts = array_values(array_filter(explode("/", trim($path, "/")), 'strlen'));
$node =& $items[0];
foreach ($parts as $p) {
    foreach ($node["children"] as &$ch) {
        if ($ch["name"] === $p && $ch["type"] === "dir") {
            $node =& $ch;
            break;
        }
    }
}

$from = $node["children"];
$node["children"] = [];
$target =& $items[0]["children"][0];
$target["children"][0]["name"] = "js_moved";
$target["children"][0]["children"] = $from;

echo "OK\n";
?>
--EXPECT--
