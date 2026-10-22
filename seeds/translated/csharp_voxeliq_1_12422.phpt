--TEST--
Translated seed (inferredbugs-csharp: voxeliq/1)
--FILE--
<?php
$f = new stdClass();
$f->title = 'VoxeliqClient2';
$f->count = 0;
for ($i = 0; $i < 3; $i++) {
    $f->count += $i;
}
echo $f->title . ':' . $f->count . PHP_EOL;
?>
--EXPECT--
