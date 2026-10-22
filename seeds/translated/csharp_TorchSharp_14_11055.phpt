--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/14)
--FILE--
<?php
$shape = [];
if (!$shape) {
    $shape = [0];
}
$length = array_product($shape);
if ($length === false) {
    $length = 0;
}
$buffer = array_fill(0, $length, 0);
$clone = $buffer;
$cloneEmpty = array_fill(0, count($shape), 0);
if (count($shape) === 0) {
    $shape = [0];
}
if (count($shape) === 1) {
    $reshaped = $buffer;
} elseif (count($shape) === 2) {
    $reshaped = [$buffer];
} else {
    $reshaped = $buffer;
}
echo "";
?>
--EXPECT--
