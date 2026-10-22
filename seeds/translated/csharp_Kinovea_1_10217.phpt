--TEST--
Translated seed (inferredbugs-csharp: Kinovea/1)
--FILE--
<?php
$gif = base64_decode('R0lGODlhAgACAPAAAP///wAAACH5BAAAAAAALAAAAAACAAIAAAIDhI9WADs=');
$file = tempnam(sys_get_temp_dir(), 'gif');
file_put_contents($file, $gif);

$im = imagecreatefromgif($file);
$w = imagesx($im);
$h = imagesy($im);

for ($i = 0; $i < 3; $i++) {
    $dst = imagecreatetruecolor($w, $h);
    imagecopy($dst, $im, 0, 0, 0, 0, $w, $h);
    imagegif($dst);
    imagedestroy($dst);
}

imagedestroy($im);
unlink($file);
?>
--EXPECT--
