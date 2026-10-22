--TEST--
Translated seed (inferredbugs-csharp: SaintCoinach/1)
--FILE--
<?php
$nr = 123456;
if ($nr < 0 || $nr > 999999) {
    exit;
}

$lang = 'en';
if (strlen($lang) > 0) {
    $lang .= '/';
}

$path = sprintf('ui/icon/%03d000/%s%06d.tex', intdiv($nr, 1000), $lang, $nr);
$files = [
    'ui/icon/123000/en/123456.tex' => 'localized',
    'ui/icon/123000/123456.tex' => 'generic',
];

$file = $files[$path] ?? null;
if ($file === null) {
    $path = sprintf('ui/icon/%03d000/%06d.tex', intdiv($nr, 1000), $nr);
    $file = $files[$path] ?? null;
}

echo $file ?? '';
?>
--EXPECT--
