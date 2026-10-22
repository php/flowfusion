--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/21)
--FILE--
<?php
if ($argc < 2 || !is_file($argv[1])) {
    exit(0);
}
$f = $argv[1];
if (!preg_match('~^https?://~i', $f)) {
    $p = pathinfo($f);
    $ext = $p['extension'] ?? '';
    $dir = $p['dirname'] ?? '';
    $base = $p['basename'] ?? '';
    $s = filesize($f);
    $w = 0;
    $h = 0;
    $size = [$w, $h];
    if (($size[0] === 0 && $size[1] === 0) || $size[0] !== $size[1]) {
        if ($size[0] === 0) {
            $r = $w / (float)$size[0];
            $size = [(int)($w * $r), (int)($h * $r)];
        }
        if ($size[1] === 0) {
            $r = $h / (float)$size[1];
            $size = [(int)($w * $r), (int)($h * $r)];
        }
    }
    $tmp = tempnam(sys_get_temp_dir(), 'v');
    $png = preg_replace('~\.[^.]*$~', '.png', $tmp);
    if (is_file($tmp)) unlink($tmp);
    $im = imagecreatetruecolor(max(1, $size[0] ?: 1), max(1, $size[1] ?: 1));
    imagepng($im, $png);
    imagedestroy($im);
    $im2 = imagecreatefrompng($png);
    unlink($png);
    imagedestroy($im2);
}
?>
--EXPECT--
