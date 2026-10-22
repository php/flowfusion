--TEST--
Translated seed (inferredbugs-csharp: storage/2)
--FILE--
<?php
$file = tempnam(sys_get_temp_dir(), 'zipbug_');
@unlink($file);

$zip = new ZipArchive();
$zip->open($file, ZipArchive::CREATE);
$zip->addFromString("a/b.txt", "x");
$zip->addFromString("a//c.txt", "y");
$zip->addFromString("d.txt", "z");
$zip->close();

$zip = new ZipArchive();
$zip->open($file, ZipArchive::RDONLY);
$names = [];
for ($i = 0; $i < $zip->numFiles; $i++) {
    $names[] = $zip->getNameIndex($i);
}
foreach (["a/b.txt", "a//c.txt", "missing.txt", "d.txt"] as $n) {
    $zip->locateName($n);
}
$zip->close();

$zip = new ZipArchive();
$zip->open($file, ZipArchive::RDWR);
$zip->deleteName("a/b.txt");
$zip->addFromString("a/b.txt", str_repeat("q", 1024));
$zip->addFromString("e.txt", "");
$zip->close();

$zip = new ZipArchive();
$zip->open($file, ZipArchive::RDONLY);
for ($i = 0; $i < $zip->numFiles; $i++) {
    $stat = $zip->statIndex($i);
    $stream = $zip->getStream($stat['name']);
    if ($stream !== false) {
        stream_get_contents($stream);
        fclose($stream);
    }
}
$zip->close();

@unlink($file);
?>
--EXPECT--
