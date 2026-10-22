--TEST--
Translated seed (inferredbugs-java: commons-compress/19)
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

// Unarchive the same
$results = [];
$zip = new ZipArchive();
$zip->open($output);
$tempDir = sys_get_temp_dir() . '/dir-result/';
mkdir($tempDir, 0777, true);

for ($i = 0; $i < $zip->numFiles; $i++) {
    $entry = $zip->getNameIndex($i);
    $outfile = $tempDir . 'result/' . $entry;
    mkdir(dirname($outfile), 0777, true);
    copy("zip://$output#$entry", $outfile);
    $results[] = $outfile;
}
$zip->close();

$file1Size = filesize($file1);
$file2Size = filesize($file2);
$result1Size = filesize($results[0]);
$result2Size = filesize($results[1]);

$correct = ($file1Size === $result1Size) && ($file2Size === $result2Size);
--EXPECT--
