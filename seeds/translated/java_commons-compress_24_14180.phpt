--TEST--
Translated seed (inferredbugs-java: commons-compress/24)
--FILE--
<?php
$output = 'bla.cpio';
$file1 = 'test1.xml';
$file2 = 'test2.xml';

$out = fopen($output, 'wb');
$os = fopen('php://temp', 'r+');

fwrite($os, pack('A100A8A8A8', $file1, sprintf('%08o', filesize($file1)), sprintf('%08o', time()), sprintf('%08o', 0)));
fwrite($os, file_get_contents($file1));
$osSize = ftell($os);
fwrite($os, pack('A', str_repeat("\0", 512 - $osSize % 512)));
fwrite($os, pack('A100A8A8A8', $file2, sprintf('%08o', filesize($file2)), sprintf('%08o', time()), sprintf('%08o', 0)));
fwrite($os, file_get_contents($file2));
$osSize = ftell($os);
fwrite($os, pack('A', str_repeat("\0", 512 - $osSize % 512)));

// Write the content to the output file
rewind($os);
stream_copy_to_stream($os, $out);
fclose($os);
fclose($out);

// Unarchive Operation
$in = fopen($output, 'rb');
while (!feof($in)) {
    $header = fread($in, 110);
    if (strlen($header) < 110) break;
    $name = trim(substr($header, 0, 100));
    $size = octdec(trim(substr($header, 108, 8)));
    $content = fread($in, $size);
    file_put_contents($name, $content);
    fread($in, 512 - ($size % 512)); // padding
}
fclose($in);
?>
--EXPECT--
