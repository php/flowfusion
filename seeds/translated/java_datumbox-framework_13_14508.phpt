--TEST--
Translated seed (inferredbugs-java: datumbox-framework/13)
--FILE--
<?php

$trainingData = [
    ['x' => ['a' => 1, 'b' => 2], 'y' => 'item1'],
    ['x' => ['a' => 2, 'b' => 3], 'y' => 'item2'],
    ['x' => ['a' => 3, 'b' => 4], 'y' => 'item3']
];

$similarities = [];

function euclidean($x1, $x2) {
    return sqrt(array_sum(array_map(function($a, $b) { return ($a - $b) ** 2; }, array_values($x1), array_values($x2))));
}

foreach ($trainingData as $r1) {
    foreach ($trainingData as $r2) {
        if ($r1['y'] === $r2['y']) {
            continue;
        }
        $key = [$r1['y'], $r2['y']];
        if (isset($similarities[serialize($key)])) {
            continue;
        }
        $similarity = 1 / (1 + euclidean($r1['x'], $r2['x']));
        if ($similarity > 0) {
            $similarities[serialize($key)] = $similarity;
            $similarities[serialize([$r2['y'], $r1['y']])] = $similarity;
        }
    }
}

$recommendations = [];

foreach ($trainingData as $r) {
    foreach ($r['x'] as $row => $score) {
        foreach ($similarities as $key => $similarity) {
            $tpk = unserialize($key);
            if ($tpk[0] !== $row) {
                continue;
            }
            $column = $tpk[1];
            if (isset($r['x'][$column])) {
                continue;
            }
            $recommendations[$column] = ($recommendations[$column] ?? 0) + $similarity * $score;
        }
    }
}

foreach ($recommendations as $column => $score) {
    echo "Recommend {$column} with score {$score}\n";
}
--EXPECT--
