--TEST--
Translated seed (inferredbugs-csharp: RPGCore/3)
--FILE--
<?php
$recipe = [
    ["x", "x", "x"],
    ["x", null, null],
];

$rotations = [
    $recipe,
    [
        ["x", "x"],
        [null, "x"],
        [null, "x"],
    ],
    [
        [null, null, "x"],
        ["x", "x", "x"],
    ],
    [
        ["x", null],
        ["x", null],
        ["x", "x"],
    ],
];

$orientation = 1;
$rotated = $rotations[$orientation % 4];

$board = [];
for ($y = 0; $y < 6; $y++) {
    for ($x = 0; $x < 6; $x++) {
        $board[$y][$x] = ["Resource" => null, "Building" => null];
    }
}

$offsetX = 1;
$offsetY = 2;
$buildingX = 3;
$buildingY = 1;

for ($x = 0; $x < count($rotated[0]); $x++) {
    for ($y = 0; $y < count($rotated); $y++) {
        $px = $offsetX + $x;
        $py = $offsetY + $y;
        if ($rotated[$y][$x] !== null) {
            $board[$py][$px]["Resource"] = "x";
        }
    }
}

$board[$buildingY][$buildingX]["Building"] = $recipe;

echo "ok\n";
?>
--EXPECT--
