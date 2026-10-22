--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/28)
--FILE--
<?php
$items = [];
for ($i = 0; $i < 3; $i++) {
    $items[] = str_repeat("x", $i * 2);
}
$layout = [
    "allowDrop" => true,
    "spacing" => [6, 6],
    "rows" => [
        [
            "cells" => [
                [
                    "rows" => [
                        [
                            "orientation" => "vertical",
                            "spacing" => 6,
                            "items" => [
                                "Plugins",
                                $items,
                                ["readOnly" => true, "path" => "/tmp/in"],
                                [
                                    "orientation" => "horizontal",
                                    "spacing" => 6,
                                    "items" => [
                                        ["text" => "Select Input..."],
                                        ["text" => "Sub Directories"]
                                    ]
                                ],
                                ["readOnly" => true, "path" => "/tmp/out"],
                                ["text" => "Select Output..."]
                            ]
                        ],
                        [
                            "rows" => [
                                ["scaleHeight" => true],
                                ["text" => "Execute"]
                            ]
                        ]
                    ]
                ],
                [
                    "spacing" => [6, 6],
                    "rows" => [
                        "Avg time per file:",
                        ["readOnly" => true, "bg" => "black", "fg" => "neonGreen", "text" => ""]
                    ]
                ]
            ]
        ]
    ]
];
echo "";
?>
--EXPECT--
