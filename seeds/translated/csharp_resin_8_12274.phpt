--TEST--
Translated seed (inferredbugs-csharp: resin/8)
--FILE--
<?php
$words = ["baby","bad","bank","box","dad","daddy","dance","dancing"];
$file = "0.bt";
file_put_contents($file, implode("\n", $words) . "\n");

$data = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$has = array_flip($data);

$near = [];
foreach ($data as $w) {
    if (levenshtein("bazy", $w) <= 1) $near[$w] = true;
}
if (count($near) !== 1 || !isset($near["baby"])) exit(1);

$near = [];
foreach ($data as $w) {
    if (levenshtein("bazy", $w) <= 2) $near[$w] = true;
}
if (count($near) !== 3 || !isset($near["baby"], $near["bank"], $near["bad"])) exit(1);

$pref = [];
foreach ($data as $w) {
    if (str_starts_with($w, "ba")) $pref[$w] = true;
}
if (count($pref) !== 3 || !isset($pref["baby"], $pref["bad"], $pref["bank"])) exit(1);

if (!isset($has["baby"]) || isset($has["bab"])) exit(1);

if (count(array_filter($data, fn($w) => levenshtein("ba", $w) <= 1)) !== 0) exit(1);
if (count(array_filter($data, fn($w) => levenshtein("ba", $w) <= 2)) !== 0) exit(1);

$tree = [];
foreach (["xxx","baby","dad"] as $w) $tree[$w] = true;
if (!isset($tree["xxx"], $tree["baby"], $tree["dad"])) exit(1);
?>
--EXPECT--
