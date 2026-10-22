--TEST--
Translated seed (inferredbugs-csharp: cscs/16)
--FILE--
<?php
$xs = [];
for ($i = 0; $i < 4; $i++) {
    $xs[] = ["k" => $i, "v" => str_repeat((string)$i, $i + 1)];
}

$map = [];
foreach ($xs as $row) {
    $key = $row["k"] % 2 ? "odd" : "even";
    $map[$key][] = $row["v"];
}

$copy = $map;
$copy["even"][0] .= "x";
$copy["odd"] = array_values(array_filter($copy["odd"], fn($s) => $s !== "11"));

$flat = [];
foreach ($copy as $group) {
    foreach ($group as $item) {
        $flat[] = $item;
    }
}

$joined = implode(",", $flat);
$parts = explode(",", $joined);

$types = [];
foreach ($parts as $p) {
    $types[] = gettype($p);
}

$nested = ["a" => ["b" => [1, 2, 3]], "c" => ["d" => ["e" => "5"]]];
$nested["a"]["b"][1] += 7;
$nested["c"]["d"]["e"] = (int)$nested["c"]["d"]["e"] + 1;

echo count($types) . "\n";
echo $nested["a"]["b"][1] . "\n";
echo $nested["c"]["d"]["e"] . "\n";
echo $joined . "\n";
?>
--EXPECT--
