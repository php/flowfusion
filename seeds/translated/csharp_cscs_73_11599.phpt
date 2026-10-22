--TEST--
Translated seed (inferredbugs-csharp: cscs/73)
--FILE--
<?php
$lines = ["a,b,c", "a,d,e", "b,f,g", "b,h,i", "c,j,k"];
$allTokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $allTokens[] = explode(',', $lines[$i]);
}
$map = [];
for ($i = 0; $i < count($allTokens); $i++) {
    $key = $allTokens[$i][0];
    if (!array_key_exists($key, $map)) {
        $map[$key] = [];
    }
    $map[$key][] = $i;
}
$counts = [];
$current = null;
$cnt = 0;
for ($i = 0; $i < count($allTokens); $i++) {
    $v = $allTokens[$i][1];
    if ($v !== $current) {
        $current = $v;
        $cnt = 0;
    }
    $counts[] = $cnt;
    $cnt++;
}
$nested = [];
$nested["x"] = ["y" => ["z" => 1]];
$copy = $nested;
$copy["x"]["y"]["z"] += 1;
$merged = array_merge($map, ["z" => $counts]);
echo count($merged) . "\n";
echo $copy["x"]["y"]["z"] . "\n";
echo $counts[0] . $counts[1] . $counts[2] . $counts[3] . $counts[4] . "\n";
?>
--EXPECT--
