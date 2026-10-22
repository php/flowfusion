--TEST--
Translated seed (inferredbugs-csharp: Masuit.LuceneEFCore.SearchEngine/1)
--FILE--
<?php
$docs = [];
for ($i = 0; $i < 2000; $i++) {
    $docs[] = [
        'Id' => (string)$i,
        'FirstName' => 'First' . $i,
        'Surname' => 'Surname' . $i,
        'Email' => 'ghudson0@rambler.ru',
        'JobTitle' => 'Job' . $i,
    ];
}

$count = count($docs);
$docs[] = [
    'Id' => '2000',
    'FirstName' => 'New',
    'Surname' => 'User',
    'Email' => 'new@example.com',
    'JobTitle' => 'Engineer',
];
$count = count($docs);

array_splice($docs, 0, 1);
$count = count($docs);

$hits = [];
foreach ($docs as $d) {
    if ($d['Email'] === 'ghudson0@rambler.ru') {
        $hits[] = $d;
    }
}

$doc = $hits[0];
$doc['FirstName'] = 'Duke';
$doc['Surname'] = 'Nukem';

foreach ($docs as &$d) {
    if ($d['Id'] === $doc['Id']) {
        $d = $doc;
        break;
    }
}
unset($d);

$hits = [];
foreach ($docs as $d) {
    if ($d['Email'] === 'ghudson0@rambler.ru') {
        $hits[] = $d;
    }
}

$first = $hits[0];
echo $first['Id'] . "\n";
echo $first['FirstName'] . "\n";
echo $first['Surname'] . "\n";
?>
--EXPECT--
