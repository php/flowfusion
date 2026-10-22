--TEST--
Translated seed (inferredbugs-java: acs-aem-commons/9)
--FILE--
<?php
$suffixes = ['image.jpg', 'image.png', 'image.gif'];
$qualityOptions = [0.0, 0.5, 1.0];
$imageType = 'image.jpg';
$quality = $qualityOptions[1];

$extension = pathinfo($imageType, PATHINFO_EXTENSION);
if ($extension === 'gif') {
    $quality = 255;
} else {
    $quality = 1.0;
}

$layer = imagecreatefromjpeg($imageType);
if ($layer === false) {
    die("Could not create layer");
}

header("Content-Type: image/$extension");
if ($extension === 'jpg' || $extension === 'jpeg') {
    imagejpeg($layer, null, $quality * 100);
} elseif ($extension === 'png') {
    imagepng($layer, null, (int)(9 * (1 - $quality)));
} elseif ($extension === 'gif') {
    imagegif($layer);
}

imagedestroy($layer);
--EXPECT--
