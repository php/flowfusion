--TEST--
Translated seed (inferredbugs-java: commons-compress/18)
--FILE--
<?php

$dir = sys_get_temp_dir() . '/test_' . uniqid();
mkdir($dir);

$files = [
    'testdata/test1.xml' => '<root></root>',
    'testdata/test2.xml' => '<root></root>',
    'test/test3.xml' => '<root></root>',
    'bla/test4.xml' => '<root></root>',
    'test.txt' => 'Hello World',
    'something/bla' => '',
    'test with spaces.txt' => 'Sample text'
];

foreach ($files as $path => $content) {
    $fullPath = $dir . '/' . $path;
    mkdir(dirname($fullPath), 0755, true);
    file_put_contents($fullPath, $content);
}

$zipFile = tempnam(sys_get_temp_dir(), 'archive') . '.zip';
$zip = new ZipArchive();
$zip->open($zipFile, ZipArchive::CREATE);

foreach ($files as $path => $content) {
    $zip->addFile($dir . '/' . $path, $path);
}

$zip->close();

$zip = new ZipArchive();
$zip->open($zipFile);

$expected = array_keys($files);
while ($zip->getNameIndex($i) !== false) {
    $entry = $zip->getNameIndex($i);
    if (($key = array_search($entry, $expected)) !== false) {
        unset($expected[$key]);
    }
    $i++;
}

$zip->close();

if (count($expected) > 0) {
    foreach ($expected as $name) {
        echo "Expected entry: $name\n";
    }
}

rmdir($dir);
unlink($zipFile);
?>
--EXPECT--
