--TEST--
Translated seed (inferredbugs-csharp: NexusForever/1)
--FILE--
<?php
$bags = [0 => [], 1 => []];
$max = 4294967295;
$bags[0][0] = ["id" => 1, "stack" => $max - 1];
$bags[0][1] = ["id" => 1, "stack" => 3];
$bags[1][0] = ["id" => 2, "stack" => 1];

$src = &$bags[0][0];
$dst = &$bags[0][1];
$newStack = min($dst["stack"] + $src["stack"], $max);
$oldStack = $src["stack"] - ($newStack - $dst["stack"]);
$dst["stack"] = $newStack;
if ($oldStack === 0) {
    unset($bags[0][0]);
} else {
    $src["stack"] = $oldStack;
}

$src = &$bags[0][1];
$dst = &$bags[1][0];
if ($src["id"] !== $dst["id"]) {
    $tmp = $src;
    $bags[0][1] = $dst;
    $bags[1][0] = $tmp;
}

$bags[0][2] = ["id" => 3, "stack" => 5];
$split = 2;
if ($split > 0 && $split < $bags[0][2]["stack"]) {
    $bags[1][1] = ["id" => $bags[0][2]["id"], "stack" => $split];
    $bags[0][2]["stack"] -= $split;
}

$use = &$bags[0][2];
if ($use["stack"] > 0) {
    $use["stack"]--;
    if ($use["stack"] === 0) unset($bags[0][2]);
}

echo "ok\n";
--EXPECT--
