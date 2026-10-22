--TEST--
Translated seed (inferredbugs-csharp: cms/3)
--FILE--
<?php
$physicPath = __DIR__ . '/';
$siteAppPath = 'site';
$path = $argv[1] ?? '/site/list_1.html';
if (strlen($siteAppPath) > 2) {
    $sub = strlen($path) < strlen($siteAppPath) ? '' : substr($path, strlen($siteAppPath));
} else {
    $sub = $path;
}
if ($path === '/' && $siteAppPath !== '') {
    $redirect = '/' . $siteAppPath;
}
if (strrpos($path, '/') === 0) {
    if (!file_exists($physicPath . $path)) {
        $path = 'root' . $path;
        if (!file_exists($physicPath . $path)) {
            $path = null;
        }
    }
    if ($path !== null) {
        $bytes = file_get_contents($physicPath . $path);
        if ($bytes === false) $bytes = '';
    }
}
if ($path !== null) {
    if ($path === $siteAppPath) {
        $catPath = '';
        $page = 1;
    } elseif ($path === $siteAppPath . '/') {
        $redirect = substr($path, 0, strlen($path) - 1);
    } else {
        $catPath = strlen($path) > strlen($siteAppPath) ? substr($path, strlen($siteAppPath)) : $path;
        $page = 1;
        if (str_ends_with($catPath, '.html')) {
            $ls = strrpos($catPath, '/');
            $begin = $ls + 1 + strlen('list_');
            $ps = substr($catPath, $begin, strlen($catPath) - $begin - 5);
            $page = (int)$ps;
            $catPath = substr($catPath, 0, $ls);
        }
    }
}
echo "OK\n";
--EXPECT--
