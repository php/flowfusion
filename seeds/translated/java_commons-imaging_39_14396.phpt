--TEST--
Translated seed (inferredbugs-java: commons-imaging/39)
--FILE--
<?php

$jpegImageFile = 'input.jpg';
$dst = 'output.jpg';

$os = null;
$canThrow = false;

try {
    $metadata = exif_read_data($jpegImageFile);
    if ($metadata !== false) {
        $outputSet = [];

        if (isset($metadata['COMPUTED']['Aperture'])) {
            unset($metadata['COMPUTED']['Aperture']);
        }
        
        $outputSet['GPSLongitude'] = -74.0;
        $outputSet['GPSLatitude'] = 40 + 43 / 60.0;

        // Logic to write the modified Exif data back into the jpg file 
        $os = fopen($dst, 'wb');

        // Example to write out the new EXIF
        // Note: Actual implementation for writing JPEG EXIF is simplified and not functional without a proper library
        fwrite($os, json_encode($outputSet)); 

        $canThrow = true;
    } else {
        copy($jpegImageFile, $dst);
    }
} finally {
    if ($canThrow && $os !== null) {
        fclose($os);
    }
}
--EXPECT--
