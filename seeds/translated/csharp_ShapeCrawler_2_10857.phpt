--TEST--
Translated seed (inferredbugs-csharp: ShapeCrawler/2)
--FILE--
<?php
$vals = [
    [56.0, 44.0, 17.35],
    [20.4, 2.4],
    [72.7, 8.2, 3.2],
];

foreach ($vals as $series) {
    foreach ($series as $v) {
        $s = (string)$v;
        $n = (float)$s;
        if ($n !== $v) {
            throw new Exception("mismatch");
        }
    }
}

$point = 0.0;
$point = 6;
if ((float)$point !== 6.0) {
    throw new Exception("mismatch");
}
?>
--EXPECT--
