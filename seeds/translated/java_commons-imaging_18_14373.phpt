--TEST--
Translated seed (inferredbugs-java: commons-imaging/18)
--FILE--
<?php

$width = 0;
$height = 0;

// Simulating user input
$params = [
    'subimage_x' => 10,
    'subimage_y' => 10,
    'subimage_width' => -5, // Invalid width to trigger the edge case
    'subimage_height' => 10,
];

// Example TIFF dimensions
$tiffWidth = 100;
$tiffHeight = 100;

$ix0 = $params['subimage_x'] ?? null;
$iy0 = $params['subimage_y'] ?? null;
$iwidth = $params['subimage_width'] ?? null;
$iheight = $params['subimage_height'] ?? null;

if ($ix0 === null && $iy0 === null && $iwidth === null && $iheight === null) {
    // No subimage parameters
    exit;
}

$errors = [];
if ($iwidth <= 0) {
    $errors[] = "negative or zero subimage width";
}
if ($iheight <= 0) {
    $errors[] = "negative or zero subimage height";
}
if ($ix0 < 0 || $ix0 >= $tiffWidth) {
    $errors[] = "subimage x is outside raster";
}
if ($ix0 + $iwidth > $tiffWidth) {
    $errors[] = "subimage (x + width) is outside raster";
}
if ($iy0 < 0 || $iy0 >= $tiffHeight) {
    $errors[] = "subimage y is outside raster";
}
if ($iy0 + $iheight > $tiffHeight) {
    $errors[] = "subimage (y + height) is outside raster";
}            

if (count($errors) > 0) {
    foreach ($errors as $error) {
        echo $error . PHP_EOL;
    }
    exit;
}

// If we reach here, it means the subimage is valid; otherwise the script will have exited before this point.
echo "Subimage parameters are valid." . PHP_EOL;
--EXPECT--
