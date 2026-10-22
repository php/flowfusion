--TEST--
Translated seed (inferredbugs-java: acs-aem-commons/12)
--FILE--
<?php

$quality = 120; // Out of range quality
$maxQuality = 100;
$minQuality = 0;
$defaultQuality = 82;
$mimeType = 'image/jpeg'; // Example mime type

if ($quality > $maxQuality || $quality < $minQuality) {
    $quality = $defaultQuality;
}

$normalizedQuality = $quality / 100;

if ($mimeType === 'image/gif') {
    $maxQualityGIF = 255;
    $normalizedQuality *= $maxQualityGIF;
}

// Output the quality value
echo $normalizedQuality;
--EXPECT--
