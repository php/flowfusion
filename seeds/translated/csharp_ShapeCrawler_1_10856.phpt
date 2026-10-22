--TEST--
Translated seed (inferredbugs-csharp: ShapeCrawler/1)
--FILE--
<?php
$slides = [
    ['hidden' => false, 'customData' => null, 'shapes' => ['auto', 'picture', 'table', 'chart', 'group']],
    ['hidden' => false, 'customData' => null, 'shapes' => ['a', 'b', 'c', 'd', 'e', 'f']],
    ['hidden' => true, 'customData' => null, 'shapes' => ['a', 'b', 'c', 'd', 'e', 'f']],
];

$slides[0]['hidden'] = true;
if (!$slides[0]['hidden']) exit(1);

$hidden = $slides[2]['hidden'];
if (!$hidden) exit(1);

$stream = fopen('php://temp', 'r+');
fwrite($stream, random_bytes(16));
rewind($stream);
$before = stream_get_contents($stream);
ftruncate($stream, 0);
rewind($stream);
fwrite($stream, random_bytes(32));
rewind($stream);
$after = stream_get_contents($stream);
if (strlen($after) <= strlen($before)) exit(1);

$slides[1]['background'] = null;
if ($slides[1]['background'] !== null) exit(1);

$slides[0]['customData'] = 'Test custom data';
$saved = $slides[0]['customData'];
if ($saved !== 'Test custom data') exit(1);

$types = array_count_values($slides[0]['shapes']);
foreach (['auto', 'picture', 'table', 'chart', 'group'] as $t) {
    if (($types[$t] ?? 0) !== 1) exit(1);
}

$counts = [5, 6, 6, 4, 5, 4, 1, 5];
if ($counts !== [5, 6, 6, 4, 5, 4, 1, 5]) exit(1);

if ($slides[0]['customData'] === null) exit(1);

$shape = ['id' => 3, 'type' => 'picture'];
if ($shape['type'] !== 'picture') exit(1);
?>
--EXPECT--
