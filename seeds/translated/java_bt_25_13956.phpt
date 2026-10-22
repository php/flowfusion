--TEST--
Translated seed (inferredbugs-java: bt/25)
--FILE--
<?php

$rootDirectory = 'target/rt';
mkdir($rootDirectory, 0777, true);

$chunkSize = 16;
$fileName = '1-single.bin';
$fileSize = $chunkSize * 4;
$SINGLE_FILE = array_merge(
    range(1, 8),
    range(1, 8),
    range(1, 4),
    range(1, 8)
);

$chunks = [];
for ($i = 0; $i < 4; $i++) {
    $chunks[] = [
        'data' => array_slice($SINGLE_FILE, $i * 16, 16),
        'written' => []
    ];
}

function writeBlock(&$chunk, $data, $offset) {
    for ($i = 0; $i < count($data); $i++) {
        if ($offset + $i < count($chunk['data'])) {
            $chunk['data'][$offset + $i] = $data[$i];
        }
    }
    $chunk['written'][] = $offset;
}

function verifyChunk($chunk) {
    return $chunk['data'] === array_merge(...array_map(function ($offset) use ($chunk) {
        return array_slice($chunk['data'], $offset, 8);
    }, $chunk['written']));
}

writeBlock($chunks[0], range(1, 8), 0);
writeBlock($chunks[0], range(1, 8), 8);
verifyChunk($chunks[0]);

writeBlock($chunks[1], range(1, 4), 0);
writeBlock($chunks[1], range(1, 4), 4);
writeBlock($chunks[1], range(1, 4), 8);
writeBlock($chunks[1], range(1, 4), 12);
verifyChunk($chunks[1]);

writeBlock($chunks[2], range(1, 11), 5);
writeBlock($chunks[2], range(1, 3), 2);
writeBlock($chunks[2], range(1, 2), 0);
verifyChunk($chunks[2]);

writeBlock($chunks[3], range(1, 4), 4);
writeBlock($chunks[3], range(1, 4), 0);
writeBlock($chunks[3], range(1, 4), 12);
writeBlock($chunks[3], range(1, 4), 8);
verifyChunk($chunks[3]);

file_put_contents("$rootDirectory/$fileName", implode('', $SINGLE_FILE));
$file = file_get_contents("$rootDirectory/$fileName");
assert(array_values($SINGLE_FILE) === array_values(unpack('C*', $file)));
--EXPECT--
