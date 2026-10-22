--TEST--
Translated seed (inferredbugs-java: bt/24)
--FILE--
<?php

$rootDirectory = "target/rt";
mkdir($rootDirectory, 0777, true);

$SINGLE_FILE = array_merge(range(1, 8), range(1, 8), range(1, 8), range(1, 8), range(1, 8), [1, 2, 1, 2, 3, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 1, 2, 3, 4, 1, 2, 3, 4, 1, 2, 3, 4]);

$fileName = "1-single.bin";
$chunkSize = 16;
$fileSize = $chunkSize * 4;

$chunks = [
    array_slice($SINGLE_FILE, 0, 16),
    array_slice($SINGLE_FILE, 16, 16),
    array_slice($SINGLE_FILE, 32, 16),
    array_slice($SINGLE_FILE, 48, 16),
];

foreach ($chunks as &$chunk) {
    $chunkHash = sha1(implode(array_map('chr', $chunk)), true);
    $chunk['hash'] = $chunkHash;
}

$blocks = [
    [array_slice(array_map('chr', $chunks[0]), 0, 8), 0],
    [array_slice(array_map('chr', $chunks[0]), 8, 8), 8],
    [array_slice(array_map('chr', $chunks[1]), 0, 4), 0],
    [array_slice(array_map('chr', $chunks[1]), 4, 4), 4],
    [array_slice(array_map('chr', $chunks[1]), 8, 4), 8],
    [array_slice(array_map('chr', $chunks[1]), 12, 4), 12],
];

foreach ($blocks as [$data, $offset]) {
    // Simulate writing blocks
    // Writing logic here
}

file_put_contents("$rootDirectory/$fileName", implode(array_map('chr', $SINGLE_FILE)));

$file = file_get_contents("$rootDirectory/$fileName");
if ($file !== implode(array_map('chr', $SINGLE_FILE))) {
    exit(1);
}

$MULTI_FILES = [
    range(1, 8),
    range(2, 6),
    [7, 8, 9, 1, 2],
    range(1, 8),
    [7],
    [1],
];

$torrentSize = $chunkSize * count($MULTI_FILES);
$sizes = [25, 18, 5, 31, 16, 1];

$torrentName = "xyz-torrent";
$torrentDirectory = "$rootDirectory/$torrentName";
mkdir($torrentDirectory, 0777, true);

$files = [];
foreach ($MULTI_FILES as $index => $fileData) {
    $fileName = ($index + 1) . "-multi.bin";
    file_put_contents("$torrentDirectory/$fileName", implode(array_map('chr', $fileData)));
    $files[] = "$torrentDirectory/$fileName";
}

foreach ($files as $file) {
    if (!file_exists($file)) {
        exit(1);
    }
}

exit(0);
--EXPECT--
