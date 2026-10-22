--TEST--
Translated seed (inferredbugs-java: Java-Lang/8)
--FILE--
<?php

function newTemporaryFile($name) {
    $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'vmf';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    $file = $dir . DIRECTORY_SEPARATOR . $name;
    if (file_exists($file)) {
        unlink($file);
    }
    return $file;
}

$file1 = newTemporaryFile("vmf-create-1");
$file2 = newTemporaryFile("vmf-create-2");
$vmf1 = fopen($file1, 'c+');
$vmf2 = fopen($file2, 'c+');

ftruncate($vmf2, 128);
fseek($vmf1, 0);
fseek($vmf2, 0);

echo (file_exists($file1) ? "Exists" : "Not Exists") . "\n";
echo (file_exists($file2) ? "Exists" : "Not Exists") . "\n";
echo "Size of vmf1: " . filesize($file1) . "\n";
echo "Size of vmf2: " . filesize($file2) . "\n";

fclose($vmf1);
fclose($vmf2);

$vmf = fopen(newTemporaryFile("vmf-acquire-buffer"), 'c+');
fseek($vmf, 0);
$buffer = str_repeat("\0", 128);
fwrite($vmf, $buffer);
fseek($vmf, 0);
$longValue = unpack('P', fread($vmf, 8))[1];
echo "Initial long value: $longValue\n";
fseek($vmf, 0);
fwrite($vmf, pack('P', 1));

fseek($vmf, 0);
echo "Updated long value: " . unpack('P', fread($vmf, 8))[1] . "\n";
fclose($vmf);

$vmf = fopen(newTemporaryFile("vmf-acquire-overlap"), 'c+');
fseek($vmf, 0);
fwrite($vmf, pack('P', 1));
fclose($vmf);

$vmf = fopen(newTemporaryFile("vmf-reopen"), 'c+');
fseek($vmf, 0);
fwrite($vmf, pack('P', 1));
fclose($vmf);

$vmf = fopen(newTemporaryFile("vmf-reopen"), 'c+');
fseek($vmf, 0);
echo "Reopened long value: " . unpack('P', fread($vmf, 8))[1] . "\n";
fclose($vmf);

$fileClean = newTemporaryFile("vmf-cleanup");
$dir = dirname($fileClean);
$free0 = disk_free_space($dir);
$vmf = fopen($fileClean, 'c+');
$blocks = str_repeat("\0", 1024 * 1024);

fwrite($vmf, $blocks);
$free1 = disk_free_space($dir);
ftruncate($vmf, 0);
fclose($vmf);
$free2 = disk_free_space($dir);
unlink($fileClean);
$free3 = disk_free_space($dir);
echo "Free space evolution: $free0, $free1, $free2, $free3\n";
--EXPECT--
