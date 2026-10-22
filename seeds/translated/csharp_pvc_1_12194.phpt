--TEST--
Translated seed (inferredbugs-csharp: pvc/1)
--FILE--
<?php
$cwd = getcwd();
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'pvc_' . bin2hex(random_bytes(4));
mkdir($dir);
chdir($dir);

$abs = $dir . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . 'file.txt';
@mkdir(dirname($abs), 0777, true);
file_put_contents($abs, "x");

$rel = str_starts_with($abs, $cwd . DIRECTORY_SEPARATOR) ? substr($abs, strlen($cwd) + 1) : $abs;

$tmp = tempnam(sys_get_temp_dir(), 'pvc_');
file_put_contents($tmp, file_get_contents($abs));
file_get_contents($tmp);

$paths = explode(PATH_SEPARATOR, getenv('PATH') ?: '');
$bins = ['__definitely_missing__', 'also_missing'];
foreach ($bins as $b) {
    if (is_file($b)) {
        $found = realpath($b);
        break;
    }
}
if (!isset($found)) {
    foreach ($paths as $p) {
        $p = trim($p);
        foreach ($bins as $b) {
            $candidate = $p === '' ? $b : $p . DIRECTORY_SEPARATOR . $b;
            if ($p !== '' && is_file($candidate)) {
                $found = realpath($candidate);
                break 2;
            }
        }
    }
}

@unlink($tmp);
@unlink($abs);
@rmdir(dirname($abs));
@rmdir($dir);
chdir($cwd);
?>
--EXPECT--
