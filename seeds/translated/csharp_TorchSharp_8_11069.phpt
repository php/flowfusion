--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/8)
--FILE--
<?php
$sizes = [0];
$shape = $sizes;
if (count($sizes) === 0) {
    $shape = [0];
}
$len = array_product($sizes ?: [1]);
if ($len < 0) {
    throw new RuntimeException("Length cannot be negative.");
}
$buf = array_fill(0, $len, 0);
if (count($shape) === 0) {
    $shape = [0];
}
$clone = $buf;
$cloneEmpty = array_fill(0, count($buf), 0);
if ($len !== count($buf)) {
    throw new RuntimeException("length mismatch");
}
if (count($shape) === 0) {
    throw new RuntimeException("Dimensions must contain elements.");
}
$newShape = [1, max(1, $len)];
if (array_product($newShape) !== $len && $len !== 0) {
    $newShape = [$len];
}
$reshaped = $buf;
$copy = $reshaped;
$copy[0] = $copy[0] ?? 0;
$zero = array_fill(0, 0, 0);
$ints = [1, 2, 3, 4];
$longs = array_map(fn($x) => (int)$x, $ints);
$floaty = array_map(fn($x) => (float)$x, $ints);
$bytes = array_map(fn($x) => $x & 0xFF, $ints);
$shorts = array_map(fn($x) => ($x << 1) >> 1, $ints);
echo "";
?>
--EXPECT--
