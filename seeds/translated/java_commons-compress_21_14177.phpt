--TEST--
Translated seed (inferredbugs-java: commons-compress/21)
--FILE--
<?php

$output = 'bla.zip';
$file1 = 'test1.xml';
$file2 = 'test2.xml';

// Archive
$zip = new ZipArchive();
$zip->open($output, ZipArchive::CREATE);
$zip->addFile($file1, 'testdata/test1.xml');
$zip->addFile($file2, 'testdata/test2.xml');
$zip->close();

// Unarchive
$results = [];
$zip = new ZipArchive();
$zip->open($output);
$zip->extractTo('dir-result');
for ($i = 0; $i < $zip->numFiles; $i++) {
    $results[] = $zip->getNameIndex($i);
}
$zip->close();
--EXPECT--
