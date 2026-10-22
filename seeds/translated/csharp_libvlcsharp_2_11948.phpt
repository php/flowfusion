--TEST--
Translated seed (inferredbugs-csharp: libvlcsharp/2)
--FILE--
<?php
$urls = ['http://www.quirksmode.org/html5/videos/big_buck_bunny.mp4', 'http://example.com'];
$items = [];
for ($i = 0; $i < 2; $i++) {
    $u = $urls[$i];
    $h = @fopen($u, 'r');
    if ($h !== false) {
        $meta = stream_get_meta_data($h);
        $items[] = [$u, !empty($meta['wrapper_type']) ? $meta['wrapper_type'] : 'stream'];
        fclose($h);
    }
}
if (!$items) {
    $items[] = ['fallback', 'local'];
}
$selected = $items[0];
$copy = $selected;
$copy[0] .= '';
$copy[1] .= '';
unset($items);
echo $copy[0], "\n", $copy[1], "\n";
?>
--EXPECT--
