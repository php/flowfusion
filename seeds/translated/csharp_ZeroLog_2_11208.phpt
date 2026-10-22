--TEST--
Translated seed (inferredbugs-csharp: ZeroLog/2)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_roll_' . bin2hex(random_bytes(4));
@mkdir($dir, 0777, true);

$prefix = 'LogFile';
$ext = 'log';
$max = 20;

$fname = function(string $date, int $n) use ($prefix, $ext): string {
    return $prefix . '.' . $date . '.' . str_pad((string)$n, 3, '0', STR_PAD_LEFT) . ($ext === '' ? '' : '.' . $ext);
};

$date = gmdate('Ymd');
$base = $fname($date, 0);
$depDate = $base !== $fname(gmdate('Ymd', strtotime($date . ' +1 day')), 0);
$depNum = $base !== $fname($date, 1);

$n = 0;
while (is_file($dir . DIRECTORY_SEPARATOR . $fname($date, $n))) $n++;

$path = $dir . DIRECTORY_SEPARATOR . $fname($date, $n);
$h = fopen($path, 'ab');
if ($h === false) exit(0);

for ($i = 0; $i < 3; $i++) {
    if ($depNum && ftell($h) >= $max) {
        fclose($h);
        $n++;
        $path = $dir . DIRECTORY_SEPARATOR . $fname($date, $n);
        $h = fopen($path, 'ab');
        if ($h === false) exit(0);
    }
    fwrite($h, str_repeat('x', 9) . "\n");
}

fclose($h);

if ($depDate) {
    $date2 = gmdate('Ymd', strtotime($date . ' +1 day'));
    $path2 = $dir . DIRECTORY_SEPARATOR . $fname($date2, 0);
    $h = fopen($path2, 'ab');
    if ($h !== false) {
        fwrite($h, "y\n");
        fclose($h);
    }
}
?>
--EXPECT--
