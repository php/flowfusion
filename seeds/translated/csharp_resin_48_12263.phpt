--TEST--
Translated seed (inferredbugs-csharp: resin/48)
--FILE--
<?php
$docs = [
    ["id" => "a", "x" => 1],
    ["id" => "b", "x" => 2],
    ["id" => "c", "x" => 3],
    ["id" => "d", "x" => 4],
];

$scores = [
    ["id" => "c", "score" => 9.5],
    ["id" => "a", "score" => 7.25],
    ["id" => "d", "score" => 7.25],
    ["id" => "b", "score" => 1.0],
];

usort($scores, function ($a, $b) {
    return $b["score"] <=> $a["score"];
});

$skip = 1;
$take = 2;
$index = $skip > 0 ? $skip : 0;
$count = $take === 0 ? count($scores) - $index : min(count($scores) - $index, $take);
$selected = array_slice($scores, $index, $count);

$lookup = [];
foreach ($docs as $doc) {
    $lookup[$doc["id"]] = $doc;
}

foreach ($selected as $row) {
    if (isset($lookup[$row["id"]])) {
        $lookup[$row["id"]]["___score"] = $row["score"] * 100;
        $lookup[$row["id"]]["extra"] = "merged";
    }
}

$result = array_values($lookup);
usort($result, function ($a, $b) {
    return ($b["___score"] ?? 0) <=> ($a["___score"] ?? 0);
});

foreach ($result as $doc) {
    $id = $doc["id"];
    $score = $doc["___score"] ?? 0;
    $x = $doc["x"] ?? null;
    echo $id . ":" . $score . ":" . $x . PHP_EOL;
}
?>
--EXPECT--
