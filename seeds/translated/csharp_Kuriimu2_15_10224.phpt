--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/15)
--FILE--
<?php
$filteredPlugins = null;
$message = "Choose plugin";
$filterNote = "Show all plugins";
$showAllChecked = $filteredPlugins === null;
$showAllEnabled = $filteredPlugins !== null;

$pluginListPanel = [];
$okButton = ["Text" => "Ok", "Command" => null];
$viewRawButton = ["Text" => "View raw bytes", "Command" => null];
$cancelButton = ["Text" => "Cancel", "Command" => null];
$showAllCheckbox = [
    "Text" => "Show all plugins",
    "ToolTip" => $filterNote,
    "Checked" => $showAllChecked,
    "Enabled" => $showAllEnabled,
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
                ]
            ]
        ],
        [
            "ScaleHeight" => true,
            "Cells" => [
                [
                    "Content" => $pluginListPanel
                ]
            ]
        ],
        [
            "Cells" => [
                $showAllCheckbox
            ]
        ],
        [
            "Padding" => [0, 3],
            "Spacing" => [3, 3],
            "Rows" => [
                [
                    "Cells" => [
                        ["ScaleWidth" => true],
                        $okButton,
                        $viewRawButton,
                        $cancelButton
                    ]
                ]
            ]
        ]
    ]
];

echo "";
?>
--EXPECT--
