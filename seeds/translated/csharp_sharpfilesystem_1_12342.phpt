--TEST--
Translated seed (inferredbugs-csharp: sharpfilesystem/1)
--FILE--
<?php
$root = '/';
$p = $root . 'a/';
$p = $p . 'b/';
$p = $p . 'c.txt';
$parent = substr($p, 0, strrpos(substr($p, 0, -1), '/') + 1);
$child = substr($p, strlen($parent) - 1);
$ext = strrchr(basename($p), '.');
if ($ext === false) $ext = '';
$changed = $parent . substr(basename($p), 0, strlen(basename($p)) - strlen($ext)) . '.log';
$segments = array_values(array_filter(explode('/', trim($parent, '/')), 'strlen'));
$segments2 = array_values(array_filter(explode('/', trim($changed, '/')), 'strlen'));
sort($segments);
sort($segments2);
echo $root, $p, $parent, $child, $ext, $changed, implode(',', $segments), implode(',', $segments2);
?>
--EXPECT--
