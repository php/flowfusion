--TEST--
Translated seed (inferredbugs-csharp: resin/44)
--FILE--
<?php
$docs = [
    ['collectionId' => 1, 'docId' => 9223372036854775807, 'score' => 0.1],
    ['collectionId' => 1, 'docId' => -1, 'score' => 0.9],
    ['collectionId' => 2, 'docId' => 0, 'score' => 0.5],
    ['collectionId' => 1, 'docId' => 42, 'score' => 0.9],
];

usort($docs, function ($a, $b) {
    return $b['score'] <=> $a['score'];
});

$skip = 1;
$take = 2;
$skip = $skip > 0 ? $skip : 0;
$take = $take > 0 ? $take : count($docs);
$docs = array_slice($docs, $skip, min(count($docs), $take));

$result = [];
foreach ($docs as $d) {
    $key = $d['collectionId'] . ':' . $d['docId'];
    $result[$key] = [
        '___docid' => $d['docId'],
        '___score' => $d['score'],
        'collectionId' => $d['collectionId'],
    ];
}

echo json_encode($result, JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
