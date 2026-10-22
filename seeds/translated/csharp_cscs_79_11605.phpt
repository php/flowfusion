--TEST--
Translated seed (inferredbugs-csharp: cscs/79)
--FILE--
<?php
$lines = ["a,b,c", "d,e,f", "g,h,i"];
$rows = [];
foreach ($lines as $line) {
    $rows[] = explode(",", $line);
}
$map = [];
foreach ($rows as $i => $row) {
    $map[$row[0]] = $i;
    $map[$row[0] . "_alias"] = $i;
}
$nested = ["x" => ["y" => ["z" => 1]]];
$nested["x"]["y"]["z"] += 1;
$nested["x"]["y"]["w"] = ["p" => 3];
$copy = $nested;
$copy["x"]["y"]["z"] = 99;
$vals = [];
for ($i = 0; $i < count($rows); $i++) {
    $vals[] = $rows[$i][1] ?? "";
}
$idx = array_search("e", $vals, true);
$type = gettype($nested);
$size = count($rows);
echo $map["a"] . " " . $map["a_alias"] . " " . $nested["x"]["y"]["z"] . " " . $copy["x"]["y"]["z"] . " " . $idx . " " . $type . " " . $size . PHP_EOL;
?>
--EXPECT--
