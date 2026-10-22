--TEST--
Translated seed (inferredbugs-csharp: NEventStore/2)
--FILE--
<?php
$tracker = [];
$streamId = 'stream-1';
$head = ['commitSequence' => 10, 'streamRevision' => 20];
$tracker[$streamId] = $head;

$attempts = [
    ['streamId' => $streamId, 'commitSequence' => 10, 'streamRevision' => 21, 'events' => [1]],
    ['streamId' => $streamId, 'commitSequence' => 11, 'streamRevision' => 20, 'events' => [1]],
    ['streamId' => $streamId, 'commitSequence' => 12, 'streamRevision' => 22, 'events' => [1, 2]],
    ['streamId' => $streamId, 'commitSequence' => 13, 'streamRevision' => 23, 'events' => []],
];

foreach ($attempts as $attempt) {
    if (empty($attempt['events'])) {
        continue;
    }

    if (isset($tracker[$attempt['streamId']])) {
        $head = $tracker[$attempt['streamId']];
        if ($head['commitSequence'] >= $attempt['commitSequence']) {
            continue;
        }
        if ($head['streamRevision'] >= $attempt['streamRevision']) {
            continue;
        }
        if ($head['commitSequence'] < $attempt['commitSequence']) {
            continue;
        }
        if ($head['streamRevision'] < $attempt['streamRevision'] - count($attempt['events'])) {
            continue;
        }
    }

    $tracker[$attempt['streamId']] = [
        'commitSequence' => $attempt['commitSequence'],
        'streamRevision' => $attempt['streamRevision'],
    ];
}

echo "ok\n";
--EXPECT--
