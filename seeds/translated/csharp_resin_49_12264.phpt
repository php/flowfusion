--TEST--
Translated seed (inferredbugs-csharp: resin/49)
--FILE--
<?php
$docs = [
    ['id' => 'a', 'score' => 30, 'x' => 1],
    ['id' => 'b', 'score' => 10, 'y' => 2],
    ['id' => 'c', 'score' => 20, 'z' => 3],
];

$index = [];
foreach ($docs as $d) {
    $index[$d['id']] = $d;
}

$lookup = [];
foreach ($docs as $d) {
    $tokens = str_split((string)$d['id']);
    $q = [];
    foreach ($tokens as $t) {
        $q[] = $t;
    }
    $lookup[] = $q;
}

$second = [];
foreach ($lookup as $q) {
    foreach ($q as $t) {
        if (isset($index[$t])) {
            $second[] = $index[$t];
        }
    }
}

foreach ($second as $record) {
    $key = $record['id'];
    if (isset($index[$key])) {
        foreach ($record as $k => $v) {
            if (!array_key_exists($k, $index[$key])) {
                $index[$key][$k] = $v;
            }
        }
    }
}

usort($docs, function ($a, $b) {
    return $b['score'] <=> $a['score'];
});

$result = [];
foreach ($index as $doc) {
    $doc['___score'] = $doc['score'] * 100;
    $result[] = $doc;
}

usort($result, function ($a, $b) {
    return $b['___score'] <=> $a['___score'];
});

echo count($result), "\n";
foreach ($result as $doc) {
    echo $doc['id'], " ", $doc['___score'], "\n";
}
?>
--EXPECT--
