--TEST--
Translated seed (inferredbugs-csharp: atldotnet/1)
--FILE--
<?php
$src = "A\x00B\x00C\x00D\x00";
$in = fopen('php://memory', 'r+');
fwrite($in, $src);
rewind($in);

$out = fopen('php://memory', 'r+');
$len = strlen($src);
for ($i = 0; $i < $len; $i++) {
    $b = fread($in, 1);
    if ($b === '') break;
    if ($b === "\xFF") {
        $n = fread($in, 1);
        if ($n === "\x00") continue;
        if ($n !== '') fwrite($out, $n);
        fwrite($out, $b);
    } else {
        fwrite($out, $b);
    }
}

rewind($out);
$data = stream_get_contents($out);

$bytes = array_values(unpack('C*', $data));
$sync = (($bytes[0] ?? 0) * 0x200000) + (($bytes[1] ?? 0) * 0x4000) + (($bytes[2] ?? 0) * 0x80) + ($bytes[3] ?? 0);

$seq = [ord('B'), 0, ord('C')];
$window = [];
$found = false;
$search = fopen('php://memory', 'r+');
fwrite($search, $data);
rewind($search);
while (count($window) < count($seq) && !feof($search)) $window[] = ord(fread($search, 1));
$initial = ftell($search);
while (true) {
    if ($window === $seq) { $found = true; break; }
    if (feof($search)) break;
    array_shift($window);
    $c = fread($search, 1);
    if ($c === '') break;
    $window[] = ord($c);
}
if (!$found) fseek($search, $initial);

echo $sync . "\n";
echo ($found ? "1" : "0") . "\n";
?>
--EXPECT--
