--TEST--
Translated seed (inferredbugs-csharp: sharpfilesystem/2)
--FILE--
<?php
$root = '/';
$path = $root . 'a/';
$path = $path . 'b/';
$path = $path . 'c.txt';
$parent = substr($path, 0, strrpos($path, '/') + 1);
$entity = basename(rtrim($path, '/'));
$ext = '';
$dot = strrpos($entity, '.');
if ($dot !== false) $ext = substr($entity, $dot);
$segments = array_values(array_filter(explode('/', trim($parent, '/')), 'strlen'));
$reconstructed = $root . implode('/', $segments) . (count($segments) ? '/' : '');
$changed = ($dot !== false) ? $parent . substr($entity, 0, $dot) . '.log' : $path . '.log';
$parentOf = str_starts_with($path, $root) && strlen($path) !== strlen($root);
$child = substr($path, strlen($root) - 1);
echo $root;
echo $path;
echo $parent;
echo $entity;
echo $ext;
echo $reconstructed;
echo $changed;
echo $parentOf ? '1' : '0';
echo $child;
?>
--EXPECT--
