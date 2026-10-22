--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/12)
--FILE--
<?php
$items = [];
for ($i = 0; $i < 5; $i++) {
    $items[] = ["text" => "Item $i", "image" => str_repeat(chr(65 + $i), $i + 1)];
}

$layout = [
    "toolstrip" => [
        ["tooltip" => "Save", "command" => "save"],
        ["tooltip" => "Save As", "command" => "saveas"],
        null,
        ["tooltip" => "Export image", "command" => "export"],
        ["tooltip" => "Import image data", "command" => "import"],
    ],
    "image" => [
        "background" => "DarkGreen",
        "size" => [200, -1],
        "dimensions" => ["width" => null, "height" => null],
        "selectors" => ["format" => [], "palette" => []],
    ],
    "list" => [
        "items" => $items,
        "palette" => [],
    ],
];

$serialized = serialize($layout);
$restored = unserialize($serialized);

$restored["image"]["dimensions"]["width"] = strlen($restored["toolstrip"][0]["tooltip"]);
$restored["image"]["dimensions"]["height"] = count($restored["toolstrip"]) + count($restored["list"]["items"]);

foreach ($restored["list"]["items"] as $idx => $item) {
    $restored["list"]["palette"][] = [$idx, $item["text"], strlen($item["image"])];
}

echo $restored["image"]["dimensions"]["width"] . ":" . $restored["image"]["dimensions"]["height"] . "\n";
echo count($restored["list"]["palette"]) . "\n";
?>
--EXPECT--
