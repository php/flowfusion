--TEST--
Translated seed (inferredbugs-java: commons-imaging/26)
--FILE--
<?php

$imageFile = 'path/to/your/jpeg/file.jpg';
$outputFile = 'path/to/your/output/file.iptc.add.jpg';

$params = [
    'read_thumbnails' => true // assuming we read thumbnails
];

$metadata = getPhotoshopMetadata($imageFile, $params);

$newRecords = [];
if ($metadata) {
    $keepOldIptcNonTextValues = true;
    $newBlocks = $keepOldIptcNonTextValues ? $metadata['nonIptcBlocks'] : [];

    $keepOldIptcTextValues = true;
    if ($keepOldIptcTextValues) {
        $oldRecords = $metadata['records'];
        foreach ($oldRecords as $record) {
            if ($record['iptcType'] != 'CITY' && $record['iptcType'] != 'CREDIT') {
                $newRecords[] = $record;
            }
        }
    }
}

$newRecords[] = ['iptcType' => 'CITY', 'value' => 'Albany, NY'];
$newRecords[] = ['iptcType' => 'CREDIT', 'value' => 'William Sorensen'];

$newData = ['records' => $newRecords, 'blocks' => $newBlocks];

writeIPTC($imageFile, $outputFile, $newData);

$updateMetadata = getPhotoshopMetadata($outputFile, $params);

function getPhotoshopMetadata($file, $params) {
    // Simulate fetching JPEG metadata
    return [
        'nonIptcBlocks' => [],
        'records' => []
    ];
}

function writeIPTC($inputFile, $outputFile, $data) {
    // Simulate writing IPTC data
}
?>
--EXPECT--
