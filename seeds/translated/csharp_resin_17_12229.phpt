--TEST--
Translated seed (inferredbugs-csharp: resin/17)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'collector_' . bin2hex(random_bytes(4));
@mkdir($dir);

file_put_contents($dir . DIRECTORY_SEPARATOR . 'field.tri', "\x66\x00\x6f\x00\x6f\x00\x00\x00");
file_put_contents($dir . DIRECTORY_SEPARATOR . 'field.pos', "foo 1 2\n");

$termCache = [];
$postingReaders = [];
$trieReaders = [];

$query = [
    'field' => 'field',
    'value' => 'foo',
    'fuzzy' => false,
    'prefix' => false,
    'edits' => 0,
    'children' => [],
    'terms' => [],
    'scores' => [],
    'reduced' => null
];

$fileName = $dir . DIRECTORY_SEPARATOR . $query['field'] . '.tri';
$sr = fopen($fileName, 'rb');
fread($sr, filesize($fileName));
$trieReaders[] = $sr;

$query['terms'] = [$query['value']];

foreach ($query['terms'] as $term) {
    if (!isset($termCache[$term])) {
        $fileId = $term;
        $posFile = $dir . DIRECTORY_SEPARATOR . $fileId . '.pos';
        if (!isset($postingReaders[$fileId])) {
            $postingReaders[$fileId] = fopen($posFile, 'rb');
        }
        $data = stream_get_contents($postingReaders[$fileId]);
        $postings = array_filter(array_map('trim', preg_split('/\R/', $data)), 'strlen');
        $termCache[$term] = $postings;
        $query['scores'][] = count($postings);
    }
}

$query['reduced'] = 0;
foreach ($query['scores'] as $s) {
    $query['reduced'] |= $s;
}

foreach ($postingReaders as $r) {
    fclose($r);
}
foreach ($trieReaders as $r) {
    fclose($r);
}
?>
--EXPECT--
