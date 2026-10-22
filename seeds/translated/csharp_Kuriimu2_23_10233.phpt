--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/23)
--FILE--
<?php
$items = [];
$items[] = ["toolTip" => "Save", "command" => ["image" => "Save"]];
$items[] = ["toolTip" => "Save As", "command" => ["image" => "SaveAs"]];
$items[] = ["splitter" => true];
$items[] = ["toolTip" => "Export image", "command" => ["image" => "ImageExport"]];
$items[] = ["toolTip" => "Import image data", "command" => ["image" => "ImageImport"]];

$images = [];
$images[] = ["image" => null, "text" => ""];
$images[] = ["image" => null, "text" => ""];

$width = "";
$height = "";
$formats = [];
$palettes = [];

$imageLayout = [
    "spacing" => 3,
    "orientation" => "vertical",
    "items" => [
        ["expand" => true, "horizontalAlignment" => "stretch", "view" => "imageView"],
        [
            "spacing" => [3, 3],
            "rows" => [
                ["width:", $width, "height:", $height, ["scaleWidth" => true]],
                ["format:", $formats, "palette:", $palettes, ["scaleWidth" => true]],
            ],
        ],
    ],
];

$listLayout = [
    "spacing" => 3,
    "orientation" => "vertical",
    "items" => [
        ["horizontalAlignment" => "stretch", "expand" => true, "view" => $images],
        ["expand" => true, "view" => "imagePalette"],
    ],
];

$mainLayout = [
    "spacing" => [3, 3],
    "rows" => [
        [["scaleWidth" => true, "view" => $imageLayout], $listLayout],
    ],
];

$content = [
    "rows" => [
        $items,
        $mainLayout,
    ],
];

echo "";
?>
--EXPECT--
