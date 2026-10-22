--TEST--
Translated seed (inferredbugs-csharp: NEventStore/3)
--FILE--
<?php
$streamId = bin2hex(random_bytes(16));
$tracker = [];
$head = ['commitSequence' => 1, 'streamRevision' => 10];
$tracker[$streamId] = $head;

$attempts = [
    ['streamId' => $streamId, 'commitSequence' => 1, 'streamRevision' => 10, 'events' => [1]],
    ['streamId' => $streamId, 'commitSequence' => 2, 'streamRevision' => 11, 'events' => [1]],
    ['streamId' => $streamId, 'commitSequence' => 3, 'streamRevision' => 20, 'events' => [1, 2, 3]],
    ['streamId' => $streamId, 'commitSequence' => 4, 'streamRevision' => 12, 'events' => []],
];

foreach ($attempts as $attempt) {
    if (empty($attempt['events'])) {
        continue;
    }

    $sid = $attempt['streamId'];
    if (isset($tracker[$sid])) {
        $head = $tracker[$sid];
        if ($head['commitSequence'] >= $attempt['commitSequence']) {
            continue;
        }
        if ($head['streamRevision'] >= $attempt['streamRevision']) {
            continue;
        }
        if ($head['commitSequence'] < $attempt['commitSequence'] - 1) {
            continue;
        }
        if ($head['streamRevision'] < $attempt['streamRevision'] - count($attempt['events'])) {
            continue;
        }
    }

    $tracker[$sid] = ['commitSequence' => $attempt['commitSequence'], 'streamRevision' => $attempt['streamRevision']];
}

echo "ok\n";
--EXPECT--
