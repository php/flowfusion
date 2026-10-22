--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/14)
--FILE--
<?php
$filteredPlugins = null;
$filterNote = "Filter note";
$message = "Choose plugin";

$showAllCheckbox = [
    "Text" => "Show all plugins",
    "ToolTip" => $filterNote,
    "Checked" => $filteredPlugins === null,
    "Enabled" => $filteredPlugins !== null,
];

$content = [
    "Padding" => [10, 6],
    "Spacing" => [0, 10],
    "Rows" => [
        [
            "Cells" => [
                [
                    "Text" => $message,
                    "TextAlignment" => "Center",
                ],
            ],
        ],
        [
            "ScaleHeight" => true,
            "Cells" => [
                [
                    "Content" => [
                        "HorizontalContentAlignment" => "Stretch",
                        "VerticalContentAlignment" => "Stretch",
                    ],
                ],
            ],
        ],
        [
            "Cells" => [
                $showAllCheckbox,
            ],
        ],
        [
            "Padding" => [0, 3],
            "Spacing" => [3, 3],
            "Rows" => [
                [
                    "Cells" => [
                        ["ScaleWidth" => true],
                        ["Text" => "Cancel"],
                        ["Text" => "Ok"],
                    ],
                ],
            ],
        ],
    ],
];

echo "Choose plugin\n";
echo ($showAllCheckbox["Checked"] ? "checked" : "unchecked") . "\n";
echo ($showAllCheckbox["Enabled"] ? "enabled" : "disabled") . "\n";
echo count($content["Rows"]) . "\n";
--EXPECT--
