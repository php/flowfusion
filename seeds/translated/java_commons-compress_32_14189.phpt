--TEST--
Translated seed (inferredbugs-java: commons-compress/32)
--FILE--
<?php
$zip = new ZipArchive();
$zipFile = 'bla.jar';
$dir = 'testdata/';
$file1 = 'test1.xml';
$file2 = 'test2.xml';

$zip->open($zipFile, ZipArchive::CREATE);
$zip->addFile($file1, 'testdata/test1.xml');
$zip->addFile($file2, 'testdata/test2.xml');
$zip->close();

$zip->open($zipFile);
for ($i = 0; $i < $zip->numFiles; $i++) {
    $entry = $zip->getNameIndex($i);
    $outputFile = $dir . $entry;
    mkdir(dirname($outputFile), 0777, true);
    file_put_contents($outputFile, $zip->getFromIndex($i));
}
$zip->close();
--EXPECT--
