--TEST--
Translated seed (inferredbugs-java: commons-imaging/16)
--FILE--
<?php

$imagesFolderPath = "src/test/data/images/png/3";
$imagesFolder = new DirectoryIterator($imagesFolderPath);

foreach ($imagesFolder as $imageFile) {
    if (!$imageFile->isFile() || strtolower($imageFile->getExtension()) !== 'png') {
        continue;
    }

    $lastFile = $imageFile->getPathname();
    for ($j = 0; $j < 10; $j++) {
        $image = imagecreatefrompng($lastFile);
        if ($image === false) {
            exit(1);
        }

        $tempFile = tempnam(sys_get_temp_dir(), pathinfo($imageFile->getFilename(), PATHINFO_FILENAME) . ".$j") . '.png';
        imagepng($image, $tempFile);
        imagedestroy($image);

        $lastFile = $tempFile;
    }
}
--EXPECT--
