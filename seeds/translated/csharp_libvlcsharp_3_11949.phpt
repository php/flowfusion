--TEST--
Translated seed (inferredbugs-csharp: libvlcsharp/3)
--FILE--
<?php
$u = "http://download.blender.org/peach/bigbuckbunny_movies/big_buck_bunny_480p_surround-fix.avi";
$p = parse_url($u);
$s = ($p['scheme'] ?? '') . '://' . ($p['host'] ?? '') . ($p['path'] ?? '');
$h = @fopen($s, 'rb');
if ($h) {
    stream_get_meta_data($h);
    fclose($h);
}
?>
--EXPECT--
