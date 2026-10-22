--TEST--
Translated seed (inferredbugs-csharp: Masuit.LuceneEFCore.SearchEngine/10)
--FILE--
<?php
$allData = [];
for ($i = 0; $i < 2000; $i++) {
    $allData[] = [
        'Id' => (string)$i,
        'FirstName' => 'First' . $i,
        'Surname' => 'Last' . $i,
        'Email' => "ghudson{$i}@rambler.ru",
        'JobTitle' => 'Job' . ($i % 10),
    ];
}

$index = [];
foreach ($allData as $u) {
    $index[$u['Id']] = $u;
}

if (count($index) !== 2000) {
    exit(1);
}

unset($index['0']);
if (count($index) >= count($allData)) {
    exit(1);
}

$u = $index['0'] ?? $allData[0];
$u['FirstName'] = 'Duke';
$u['Surname'] = 'Nukem';
$index[$u['Id']] = $u;

$results = [];
foreach ($index as $doc) {
    if ($doc['Email'] === 'ghudson0@rambler.ru') {
        $results[] = $doc;
    }
}

if (!$results) {
    exit(1);
}

$r = $results[0];
if ($r['Id'] !== '0' || $r['FirstName'] !== 'Duke' || $r['Surname'] !== 'Nukem') {
    exit(1);
}
?>
--EXPECT--
