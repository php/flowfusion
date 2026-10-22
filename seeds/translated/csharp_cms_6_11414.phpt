--TEST--
Translated seed (inferredbugs-csharp: cms/6)
--FILE--
<?php
$siteLocation = '';
$siteAppPath = 'news';
$path = '/news/list_2.html';
$cache = [];
$physicPath = __DIR__ . '/';

$subPath = function(string $path, string $appPath): string {
    $len = strlen($appPath);
    if ($len > 2) {
        if (strlen($path) < $len) return '';
        return substr($path, $len);
    }
    return $path;
};

$checkSiteUrl = function(string $path) use (&$siteLocation, &$siteAppPath): array {
    if ($siteLocation !== '') return [false, $siteLocation];
    if ($siteAppPath !== '' && $path === '/') return [false, '/' . $siteAppPath];
    return [true, ''];
};

if (strlen($path) > 0 && $path[strlen($path) - 1] === '/') {
    $path = substr($path, 0, -1);
}

[$ok, $redirect] = $checkSiteUrl($path);
if ($ok) {
    $catPath = $subPath($path, '/' . $siteAppPath);
    $page = 1;
    if (str_ends_with($catPath, '.html')) {
        $ls = strrpos($catPath, '/');
        $begin = $ls + 1 + strlen('list_');
        $ps = substr($catPath, $begin, strlen($catPath) - $begin - 5);
        $page = (int)$ps;
        $catPath = substr($catPath, 0, $ls);
    }

    $archivePath = $subPath('/news/article.html', '/' . $siteAppPath);
    $archivePath = substr($archivePath, 0, strrpos($archivePath, '.'));
    $cache['index'] = '<html>ok</html>';
    $cache['archive'] = $archivePath . ':' . $page . ':' . $catPath;
}

echo "done";
?>
--EXPECT--
