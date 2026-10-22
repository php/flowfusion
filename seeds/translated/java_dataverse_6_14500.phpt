--TEST--
Translated seed (inferredbugs-java: dataverse/6)
--FILE--
<?php

$fileId = 9223372036854775807; // Max value for 64-bit integer
$uriInfo = ['query' => ['key1' => 'value1', 'key2' => 'value2']];
$dataFiles = [['isImage' => true, 'fileSystemLocation' => '/path/to/image1.png'],
              ['isImage' => false, 'fileSystemLocation' => '/path/to/datafile.csv']];
$defaultIcons = ['file' => 'icon_file.png', 'dataset' => 'icon_dataset.png'];
$imageThumbFileName = null;

function findDataFile($fileId) {
    return $fileId >= 0 ? ['contentType' => 'image/png', 'isTabularData' => false] : null;
}

function generateImageThumb($filePath) {
    return $filePath ? $filePath : null;
}

function getWebappImageResource($imageName) {
    return '/path/to/resources/images/' . $imageName;
}

$dataFile = findDataFile($fileId);
if ($dataFile) {
    if (strpos($dataFile['contentType'], 'image/') === 0) {
        $imageThumbFileName = generateImageThumb('/path/to/image_thumb.png');
    }
    
    if (isset($dataFiles[0]['isImage']) && $dataFiles[0]['isImage']) {
        $imageThumbFileName = generateImageThumb($dataFiles[0]['fileSystemLocation']);
    } else {
        $imageThumbFileName = getWebappImageResource($defaultIcons['file']);
    }
    
    if ($imageThumbFileName) {
        $in = @fopen($imageThumbFileName, 'rb');
        if (!$in) {
            return null;
        }
    }
} else {
    return null;
}
--EXPECT--
