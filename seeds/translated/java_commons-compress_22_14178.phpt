--TEST--
Translated seed (inferredbugs-java: commons-compress/22)
--FILE--
<?php
$output = tempnam(sys_get_temp_dir(), 'bla') . '.zip';
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
for ($i = 0; $i < $zip->numFiles; $i++) {
    $entry = $zip->getNameIndex($i);
    $resultPath = sys_get_temp_dir() . '/result/' . $entry;
    mkdir(dirname($resultPath), 0777, true);
    copy("zip://$output#$entry", $resultPath);
    $results[] = $resultPath;
}
$zip->close();

$len1 = filesize($file1);
$len2 = filesize($file2);
$resultLen1 = filesize($results[0]);
$resultLen2 = filesize($results[1]);
?>
--EXPECT--
