--TEST--
Translated seed (inferredbugs-csharp: StrongNameSigner/3)
--FILE--
<?php
$base = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_bug_pattern_' . uniqid('', true);
mkdir($base);
$f = $base . DIRECTORY_SEPARATOR . 'a.php';
file_put_contents($f, "<?php echo 1;");

$p = realpath($f);
if ($p === false || !file_exists($p)) {
    exit(1);
}

$info = [
    'path' => $p,
    'size' => filesize($p),
    'is_php' => pathinfo($p, PATHINFO_EXTENSION) === 'php',
    'is_large' => filesize($p) > 0,
];

$flags = 0;
if ($info['is_php']) $flags |= 1;
if ($info['is_large']) $flags |= 2;

switch ($flags) {
    case 3:
        $q = dirname($p);
        break;
    default:
        $q = $base;
        break;
}

$g = glob($q . DIRECTORY_SEPARATOR . '*.php');
if (!is_array($g) || count($g) !== 1) {
    exit(1);
}

unlink($f);
rmdir($base);
?>
--EXPECT--
