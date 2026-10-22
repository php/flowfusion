--TEST--
Translated seed (inferredbugs-csharp: cscs/65)
--FILE--
<?php
$lines = [
    "a\t1",
    "a\t2",
    "b\t3",
    "b\t4",
    "b\t5",
    "c\t6",
];

$tokens = [];
foreach ($lines as $i => $line) {
    $tokens[$i] = explode("\t", $line);
}

$counts = [];
$prev = null;
$run = 0;
foreach ($tokens as $row) {
    $v = $row[0];
    if ($v !== $prev) {
        $prev = $v;
        $run = 0;
    }
    $counts[] = $run;
    $run++;
}

$map = [];
foreach ($tokens as $i => $row) {
    $k = $row[0];
    if (!isset($map[$k])) {
        $map[$k] = [];
    }
    $map[$k][] = $i;
}

$nested = [];
$nested["x"] = ["y" => ["z" => 1]];
$nested["x"]["y"]["z"] += count($counts);

$copy = $nested;
$copy["x"]["y"]["z"]++;
$copy["x"]["y"]["w"] = implode(",", array_map("strval", $counts));

echo $copy["x"]["y"]["z"], "\n";
echo $copy["x"]["y"]["w"], "\n";
echo count($map["b"]), "\n";
?>
--EXPECT--
