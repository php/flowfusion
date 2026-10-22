--TEST--
Translated seed (inferredbugs-java: Saturn/1)
--FILE--
<?php

function unzip($zipFile, $directory) {
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === TRUE) {
        $zip->extractTo($directory);
        $zip->close();
    }
}

function zip($files, $directory, $zipFile) {
    $zip = new ZipArchive;
    if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
        foreach ($files as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();
    }
}

$home = getenv('HOME') . '/.saturn';
$cacheDir = $home . '/caches';
if (!file_exists($cacheDir)) {
    mkdir($cacheDir, 0777, true);
}

$zipFile = 'test.zip';
file_put_contents($zipFile, ''); // Create an empty zip file

$filesToZip = ['file1.txt', 'file2.txt']; // Replace with actual files
zip($filesToZip, $cacheDir, $zipFile);
unzip($zipFile, $cacheDir);

?>
--EXPECT--
