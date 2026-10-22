--TEST--
Translated seed (inferredbugs-java: commons-imaging/27)
--FILE--
<?php

$imageFile = 'path/to/your/image.jpg'; // Replace with a valid JPEG file path
$tempFile = tempnam(sys_get_temp_dir(), 'iptc.add.') . '.jpg';

$metadata = getPhotoshopMetadata($imageFile);
$newRecords = [];
$nonIptcBlocks = $metadata['nonIptcBlocks'] ?? [];

if ($metadata) {
    $oldRecords = $metadata['records'] ?? [];
    foreach ($oldRecords as $record) {
        if ($record['iptcType'] !== 'CITY' && $record['iptcType'] !== 'CREDIT') {
            $newRecords[] = $record;
        }
    }
}

$newRecords[] = ['iptcType' => 'CITY', 'value' => 'Albany, NY'];
$newRecords[] = ['iptcType' => 'CREDIT', 'value' => 'William Sorensen'];

$newData = ['records' => $newRecords, 'nonIptcBlocks' => $nonIptcBlocks];

$os = fopen($tempFile, 'wb');
if ($os) {
    writeIPTC($imageFile, $os, $newData);
    fclose($os);
}

$outMetadata = getPhotoshopMetadata($tempFile);

function getPhotoshopMetadata($file) {
    // Dummy function for metadata extraction
    return [
        'records' => [],
        'nonIptcBlocks' => []
    ];
}

function writeIPTC($inputFile, $outputStream, $data) {
    // Dummy function for writing IPTC data
}
--EXPECT--
