--TEST--
Translated seed (inferredbugs-java: android-maven-plugin/2)
--FILE--
<?php

$zipFile = 'test.zip';
$duplicateFile = 'duplicate.txt';

// Create a new zip file and add a file to it
$zip = new ZipArchive();
$zip->open($zipFile, ZipArchive::CREATE);
$zip->addFromString('file1.txt', 'This is file 1');
$zip->addFromString('file2.txt', 'This is file 2');
$zip->addFromString($duplicateFile, 'Duplicate content');
$zip->addFromString('META-INF/manifest.mf', 'Manifest content');
$zip->close();

// Function to add META-INF resources
function addMetaInf($zipFile, $additionalFiles) {
    $zip = new ZipArchive();
    if ($zip->open($zipFile) === TRUE) {
        $entries = [];
        foreach ($additionalFiles as $file) {
            if (strpos($file, 'META-INF/') === 0) {
                $entries[] = $file;
                $zip->addFile($file, $file);
            }
        }
        $zip->close();
    }
}

// Create a temporary zip file to modify
$tempZipFile = 'temp.zip';
copy($zipFile, $tempZipFile);

// Add META-INF resources
addMetaInf($tempZipFile, ['META-INF/manifest.mf', $duplicateFile]);

// Replace the original zip file with the modified one
unlink($zipFile);
rename($tempZipFile, $zipFile);

// Cleanup
unlink($duplicateFile);
--EXPECT--
