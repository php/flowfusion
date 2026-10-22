--TEST--
Translated seed (inferredbugs-csharp: SimdJsonSharp/1)
--FILE--
<?php
$data = file_get_contents('php://stdin');
if ($data === false || $data === '') {
    $data = '[0,42001,42000.5,-1,{"a":42002,"b":[42003,42000]}]';
}
$decoded = json_decode($data, true);

$count = 0;
$stack = [$decoded];
while ($stack) {
    $v = array_pop($stack);
    if (is_array($v)) {
        foreach ($v as $x) {
            $stack[] = $x;
        }
    } elseif (is_int($v) || is_float($v)) {
        if ($v > 42000) {
            $count++;
        }
    }
}

echo $count, PHP_EOL;
?>
--EXPECT--
