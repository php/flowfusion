--TEST--
Translated seed (inferredbugs-csharp: resin/10)
--FILE--
<?php
$words = ["baby", "bad", "bank", "box", "dad", "daddy", "dance", "dancing"];
$file = "0.bt";
file_put_contents($file, implode("\n", $words) . "\n");

$data = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$set = array_fill_keys($data, true);

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

if (!isset($set["baby"]) || isset($set["bab"])) exit(1);

$expected = "d1100\r\na0101\r\nn1102\r\nc0103\r\ni1104\r\nn0105\r\ng0016\r\ne0014\r\nd0112\r\nd0103\r\ny0014\r\nb0100\r\no1101\r\nx0012\r\na0101\r\nn1102\r\nk0013\r\nd1012\r\nb0102\r\ny0013\r\n";
file_put_contents($file, $expected);
if (file_get_contents($file) !== $expected) exit(1);
?>
--EXPECT--
