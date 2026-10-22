--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/39)
--FILE--
<?php
$rows = [
    ['name' => 'other'],
    ['name' => 'beer-sample'],
    ['name' => 'x'],
];

$dropCount = 0;
foreach ($rows as $row) {
    if ($row['name'] === 'beer-sample') {
        $dropCount++;
    }
}

$query = "CREATE PRIMARY INDEX ON `beer-sample`";
$result = [
    'success' => true,
    'rows' => [$query, $dropCount],
];

echo $result['rows'][0], "\n";
echo $result['rows'][1], "\n";

$hello = ['Greeting' => 'Hello World'];
echo $hello['Greeting'], "\n";

$badSyntax = false;
$badRows = [];
if ($badSyntax) {
    $badRows[] = 'unexpected';
}

$beers = [];
for ($i = 0; $i < 10; $i++) {
    $beers[] = [
        'name' => 'Beer ' . $i,
        'type' => 'beer',
        'abv' => 5.0 + $i,
        'meta' => ['id' => $i],
    ];
}

foreach ($beers as $beer) {
    if ($beer['type'] === 'beer' && !empty($beer['name'])) {
        echo $beer['name'], "\n";
    }
}

$params = ['beer'];
foreach ($params as $p) {
    $filtered = [];
    foreach ($beers as $beer) {
        if ($beer['type'] === $p) {
            $filtered[] = $beer;
        }
    }
    echo count($filtered), "\n";
}

$named = ['type' => 'beer'];
$filtered = [];
foreach ($beers as $beer) {
    if ($beer['type'] === $named['type']) {
        $filtered[] = $beer;
    }
}
echo count($filtered), "\n";

$prepared = [];
for ($i = 0; $i < 3; $i++) {
    $prepared[] = count($beers);
}
echo implode(',', $prepared), "\n";
?>
--EXPECT--
