--TEST--
Translated seed (inferredbugs-java: commons-compress/33)
--FILE--
<?php
$output = 'bla.jar';
$file1 = 'test1.xml';
$file2 = 'test2.xml';

$zip = new ZipArchive();
$zip->open($output, ZipArchive::CREATE);
$zip->addFile($file1, 'testdata/test1.xml');
$zip->addFile($file2, 'testdata/test2.xml');
$zip->close();

$zip = new ZipArchive();
$zip->open($output);
for ($i = 0; $i < $zip->numFiles; $i++) {
    $filename = $zip->getNameIndex($i);
    $zip->extractTo('.', $filename);
}
$zip->close();
?>
--EXPECT--
