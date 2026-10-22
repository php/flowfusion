--TEST--
Translated seed (inferredbugs-csharp: resin/30)
--FILE--
<?php
$docs = [
    1 => ['title' => 'alpha', 'body' => 'one'],
    2 => ['title' => 'beta', 'body' => 'two'],
    3 => ['title' => 'gamma', 'body' => 'three'],
];

$index = [
    'foo' => [
        ['doc' => 1, 'score' => 10.0, 'offset' => 0],
        ['doc' => 2, 'score' => 10.0, 'offset' => 1],
        ['doc' => 3, 'score' => 8.5, 'offset' => 2],
    ],
    'bar' => [
        ['doc' => 2, 'score' => 9.0, 'offset' => 3],
        ['doc' => 3, 'score' => 9.0, 'offset' => 4],
    ],
    'baz' => [
        ['doc' => 1, 'score' => 7.0, 'offset' => 5],
        ['doc' => 3, 'score' => 7.0, 'offset' => 6],
    ],
];

$query = [
    ['term' => 'foo', 'op' => 'or'],
    ['term' => 'bar', 'op' => 'and'],
    ['term' => 'baz', 'op' => 'not'],
];

$result = null;

foreach ($query as $i => $q) {
    $matches = $index[$q['term']] ?? [];
    if (!$matches) {
        $result = [];
        break;
    }

    $top = $matches[0]['score'];
    $cutoff = $top * 0.85;
    $docIds = [];

    foreach ($matches as $m) {
        if ($m['score'] < $cutoff) break;
        $docIds[$m['doc']] = ($docIds[$m['doc']] ?? 0) + $m['score'];
    }

    if ($result === null) {
        $result = $docIds;
    } elseif ($q['op'] === 'and') {
        $next = [];
        foreach ($result as $id => $score) {
            if (isset($docIds[$id])) $next[$id] = $score + $docIds[$id];
        }
        $result = $next;
    } elseif ($q['op'] === 'not') {
        foreach (array_keys($docIds) as $id) unset($result[$id]);
    } else {
        foreach ($docIds as $id => $score) {
            $result[$id] = isset($result[$id]) ? $result[$id] + $score : $score;
        }
    }
}

if ($result !== null) {
    arsort($result, SORT_NUMERIC);
    $top = array_slice($result, 0, 2, true);
    $out = [];
    foreach ($top as $id => $score) {
        $doc = $docs[$id] ?? [];
        $doc['__docid'] = $id;
        $doc['__score'] = $score;
        $out[] = $doc;
    }
}
?>
--EXPECT--
