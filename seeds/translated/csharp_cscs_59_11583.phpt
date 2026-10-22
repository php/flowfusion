--TEST--
Translated seed (inferredbugs-csharp: cscs/59)
--FILE--
<?php
$lines = ["a,b,c", "d,e,f", "g,h,i"];
$all = [];
for ($i = 0; $i < count($lines); $i++) {
    $all[] = explode(",", $lines[$i]);
}
$col = [];
for ($i = 0; $i < count($all); $i++) {
    $col[] = $all[$i][1];
}
$map = [];
for ($i = 0; $i < count($all); $i++) {
    $key = $all[$i][0];
    if (!array_key_exists($key, $map)) {
        $map[$key] = [];
    }
    $map[$key][] = $i;
}
$nested = [];
$nested["x"] = ["y" => ["z" => 1]];
$nested["x"]["y"]["z"] += 1;
$copy = $nested;
$copy["x"]["y"]["z"] = 99;
$types = [gettype($col), gettype($map), gettype($copy)];
echo implode("|", $col) . "\n";
echo implode(",", $map["a"]) . "\n";
echo $nested["x"]["y"]["z"] . "\n";
echo implode(",", $types) . "\n";
?>
--EXPECT--
