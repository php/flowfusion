--TEST--
Translated seed (inferredbugs-java: commons-compress/34)
--FILE--
<?php

$output = 'bla.ar';
$file1 = 'test1.xml';
$file2 = 'test2.xml';

// Create AR Archive
$out = fopen($output, 'wb');
$arr = array();
$arr[] = [$file1, filesize($file1)];
$arr[] = [$file2, filesize($file2)];
foreach ($arr as $entry) {
    $name = $entry[0];
    $size = $entry[1];
    fwrite($out, "AR_HEADER: $name, $size\n");
    fwrite($out, file_get_contents($name));
}
fclose($out);

// Unarchive Operation
$input = fopen($output, 'rb');
$sum = 0;
while (($line = fgets($input)) !== false) {
    if (strpos($line, 'AR_HEADER:') === 0) {
        $parts = explode(',', substr($line, 11));
        $name = trim($parts[0]);
        $length = (int)trim($parts[1]);
        $content = fread($input, $length);
        if ($name !== 'test1.xml') {
            $sum += $length;
        }
    }
}
fclose($input);

// Final Output
echo $sum;
--EXPECT--
