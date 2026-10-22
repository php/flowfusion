--TEST--
Translated seed (inferredbugs-java: commons-imaging/21)
--FILE--
<?php
$imageFiles = glob('path/to/images/*.{png,jpg,jpeg,gif}', GLOB_BRACE);
foreach ($imageFiles as $imageFile) {
    $imageFileBytes = file_get_contents($imageFile);
    if ($imageFileBytes === false) continue;

    $imageSizeFile = getimagesize($imageFile);
    $imageSizeBytes = getimagesizefromstring($imageFileBytes);
    
    if ($imageSizeFile === false || $imageSizeBytes === false) continue;

    if ($imageSizeFile[0] !== $imageSizeBytes[0] || $imageSizeFile[1] !== $imageSizeBytes[1]) continue;

    $imageFormatFile = strtolower(pathinfo($imageFile, PATHINFO_EXTENSION));
    $imageFormatBytes = strtolower(exif_imagetype($imageFile));
    
    if ($imageFormatFile !== $imageFormatBytes) continue;

    $iccFile = getICCProfile($imageFile);
    $iccBytes = getICCProfileFromBytes($imageFileBytes);
    
    if (empty($iccFile) && empty($iccBytes)) continue;
    if (empty($iccFile) !== empty($iccBytes)) continue;

    foreach (getImageInfo($imageFile) as $key => $value) {
        if ($key !== 'bits' && $key !== 'format' && $key !== 'width' && $key !== 'height') continue;
        if ($value !== getImageInfoFromBytes($imageFileBytes)[$key]) continue;
    }
}

function getICCProfile($file) {
    // Mock function to simulate ICC profile extraction
    return null;
}

function getICCProfileFromBytes($bytes) {
    // Mock function to simulate ICC profile extraction from bytes
    return null;
}

function getImageInfo($file) {
    return [
        'bits' => 24,
        'format' => 'image/jpeg',
        'width' => 800,
        'height' => 600,
    ];
}

function getImageInfoFromBytes($bytes) {
    return [
        'bits' => 24,
        'format' => 'image/jpeg',
        'width' => 800,
        'height' => 600,
    ];
}
--EXPECT--
