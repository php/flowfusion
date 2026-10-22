--TEST--
Translated seed (inferredbugs-java: commons-imaging/38)
--FILE--
<?php
$jpegImageFile = 'input.jpg';
$dst = 'output.jpg';

$outputSet = null;

$metadata = getimagesize($jpegImageFile, $info);
if ($info) {
    $exif = @exif_read_data($jpegImageFile);
    if ($exif) {
        $outputSet = $exif;
    }
}

if (!$outputSet) {
    copy($jpegImageFile, $dst);
    exit(0);
}

unset($outputSet['COMPUTED']['Aperture']); // remove aperture tag

$longitude = -74.0; 
$latitude = 40 + 43 / 60.0; 

$exif['GPSLatitude'] = $latitude;
$exif['GPSLongitude'] = $longitude;

$exif['GPSLatitudeRef'] = $latitude >= 0 ? 'N' : 'S';
$exif['GPSLongitudeRef'] = $longitude >= 0 ? 'E' : 'W';

$jpeg = imagecreatefromjpeg($jpegImageFile);
imagejpeg($jpeg, $dst, 100);
imagedestroy($jpeg);
--EXPECT--
