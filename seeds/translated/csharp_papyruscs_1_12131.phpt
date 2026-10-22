--TEST--
Translated seed (inferredbugs-csharp: papyruscs/1)
--FILE--
<?php
$base = __DIR__;
$db = $base . DIRECTORY_SEPARATOR . 'benchmark' . DIRECTORY_SEPARATOR . 'world' . DIRECTORY_SEPARATOR . 'db';
$tex = $base . DIRECTORY_SEPARATOR . 'Textures' . DIRECTORY_SEPARATOR . 'terrain_texture.json';

if (is_dir($db)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($db, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $f) {
        if ($f->isFile()) {
            file_get_contents($f->getPathname());
        }
    }
}

if (is_file($tex)) {
    $json = file_get_contents($tex);
    json_decode($json, true);
}

$chunkRadius = 1;
$centerOffsetX = 1;
$centerOffsetZ = 1;

$size = 16 * 16 * (2 * $chunkRadius + 1);
$img = imagecreatetruecolor($size, $size);
$bg = imagecolorallocate($img, 0, 0, 0);
imagefill($img, 0, 0, $bg);

for ($dz = -$chunkRadius; $dz <= $chunkRadius; $dz++) {
    for ($dx = -$chunkRadius; $dx <= $chunkRadius; $dx++) {
        $x = ($chunkRadius + $dx) * 256;
        $y = ($chunkRadius + $dz) * 256;
        $c = (($dx + $centerOffsetX) << 16) ^ (($dz + $centerOffsetZ) & 0xFFFF);
        $col = imagecolorallocate($img, ($c >> 16) & 255, ($c >> 8) & 255, $c & 255);
        imagefilledrectangle($img, $x, $y, $x + 15, $y + 15, $col);
    }
}

imagepng($img, $base . DIRECTORY_SEPARATOR . 'benchmark.png');
imagedestroy($img);

$x = 1;
$z = 1;
$k = (($x & 0xFFFFFFFF) << 32) | ($z & 0xFFFFFFFF);
echo sprintf("%08x\n", $k);
echo $k === 0x100000001 ? "" : "";
?>
--EXPECT--
