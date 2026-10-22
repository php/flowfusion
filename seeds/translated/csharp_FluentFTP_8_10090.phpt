--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/8)
--FILE--
<?php
$mode = '755';
$path = 'a/b/c.txt';
$rest = 1;
$system = "Windows_NT";
$messages = [" Size=4294967296; type=file;"];
$size = 0;

if ($rest > 0 && $system === "Windows_NT") {
    $cmd = "APPE $path";
} else {
    $cmd = $rest > 0 ? "STOR $path" : "STOR $path";
}

if (preg_match('/Size=(\d+);/i', $messages[0], $m)) {
    $size = (int)$m[1];
}

$listing = [];
if (preg_match('/"(.*)"/', '257 "/home/user" is current directory', $m)) {
    $listing[] = $m[1];
}

$time = DateTime::createFromFormat('YmdHis', '20240229123456') ?: new DateTime('@0');
$buf = str_repeat('x', 3);
$offset = 1;
$out = substr($buf, $offset);
$in = fopen('php://temp', 'r+');
fwrite($in, "hello");
rewind($in);
fseek($in, $rest, SEEK_SET);
$data = stream_get_contents($in);
fclose($in);

echo $cmd . "\n";
echo $mode . "\n";
echo $size . "\n";
echo $listing[0] . "\n";
echo $time->format('Y-m-d H:i:s') . "\n";
echo $out . "\n";
echo $data . "\n";
--EXPECT--
