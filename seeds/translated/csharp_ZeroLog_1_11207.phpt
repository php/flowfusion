--TEST--
Translated seed (inferredbugs-csharp: ZeroLog/1)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_roll_' . getmypid();
@mkdir($dir, 0777, true);

$prefix = 'LogFile';
$ext = 'log';
$maxSize = 32;

$date = gmdate('Ymd');
$findLast = function () use ($dir, $prefix, $ext, $date, $maxSize) {
    $n = 0;
    $last = null;
    while (true) {
        $name = $dir . DIRECTORY_SEPARATOR . $prefix . '.' . $date . '.' . str_pad((string)$n, 3, '0', STR_PAD_LEFT) . ($ext === '' ? '' : '.' . $ext);
        if ($name === $last) return $n;
        if (!file_exists($name)) {
            if ($n === 0) return 0;
            break;
        }
        $last = $name;
        $n++;
    }
    if ($last !== null) {
        $h = @fopen($last, 'ab');
        if ($h !== false) {
            $len = filesize($last);
            fclose($h);
            if ($maxSize > 0 && $len < $maxSize) return $n - 1;
        } else {
            return $n;
        }
    }
    return $n;
};

$curDate = $date;
$curNum = $findLast();
$file = $dir . DIRECTORY_SEPARATOR . $prefix . '.' . $curDate . '.' . str_pad((string)$curNum, 3, '0', STR_PAD_LEFT) . '.' . $ext;
$h = fopen($file, 'ab');
fwrite($h, str_repeat('x', 16));
fclose($h);

$curNum = $findLast();
$file = $dir . DIRECTORY_SEPARATOR . $prefix . '.' . $curDate . '.' . str_pad((string)$curNum, 3, '0', STR_PAD_LEFT) . '.' . $ext;
$h = fopen($file, 'ab');
fwrite($h, str_repeat('y', 20));
fclose($h);

$newDate = gmdate('Ymd', time() + 86400);
$curDate = $newDate;
$curNum = 0;
$file = $dir . DIRECTORY_SEPARATOR . $prefix . '.' . $curDate . '.' . str_pad((string)$curNum, 3, '0', STR_PAD_LEFT) . '.' . $ext;
$h = fopen($file, 'ab');
fwrite($h, "z");
fclose($h);
?>
--EXPECT--
