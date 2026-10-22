--TEST--
Translated seed (inferredbugs-csharp: cms/1)
--FILE--
<?php
$path = '/site/list_12.html';
$appPath = '/site';
$len = strlen($appPath);
if ($len > 2) {
    if (strlen($path) < $len) {
        $subPath = '';
    } else {
        $subPath = substr($path, $len);
    }
} else {
    $subPath = $path;
}
if ($path === $appPath) {
    $out = 'index';
} elseif ($path === $appPath . '/') {
    $out = substr($path, 0, strlen($path) - 1);
} else {
    $catPath = $subPath;
    $page = 1;
    if (substr($catPath, -5) === '.html') {
        $ls = strrpos($catPath, '/');
        $begin = $ls + 1 + strlen('list_');
        $ps = substr($catPath, $begin, strlen($catPath) - $begin - 5);
        $page = (int)$ps;
        $catPath = substr($catPath, 0, $ls);
    }
    $out = $catPath . ':' . $page;
}
echo $out;
?>
--EXPECT--
