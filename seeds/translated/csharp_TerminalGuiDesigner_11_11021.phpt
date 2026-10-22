--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/11)
--FILE--
<?php
$tabs = [
    ["Tab1", [["label1", "Heya", 15, 7], ["button1", "Press Me!", 5, 3], ["checkbox1", "Check me", 0, 0, true]]],
    ["Tab2", [["label2", "Label 1", 10, 3], ["label3", "Label 2", 0, 0]]],
];

$layout = [];
foreach ($tabs as [$name, $items]) {
    $view = ["name" => $name, "items" => []];
    foreach ($items as $item) {
        $view["items"][] = $item;
    }
    $layout[] = $view;
}

echo count($layout), PHP_EOL;
?>
--EXPECT--
