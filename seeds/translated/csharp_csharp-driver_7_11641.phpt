--TEST--
Translated seed (inferredbugs-csharp: csharp-driver/7)
--FILE--
<?php
$keys = ["Murmur3Partitioner", "RandomPartitioner", "OrderedPartitioner", "Unknown"];
foreach ($keys as $p) {
    $factory = null;
    if (str_ends_with($p, "Murmur3Partitioner")) {
        $factory = "m3";
    } elseif (str_ends_with($p, "RandomPartitioner")) {
        $factory = "rp";
    } elseif (str_ends_with($p, "OrderedPartitioner")) {
        $factory = "op";
    }

    if ($factory === "m3") {
        $v = PHP_INT_MIN;
        $v = ($v === PHP_INT_MIN) ? PHP_INT_MAX : $v;
        $a = $v ^ (($v >> 32) & 0xffffffff);
        $b = $v ^ (($v >> 32) & 0xffffffff);
        $c = $a <=> $b;
    } elseif ($factory === "op") {
        $x = "abc";
        $y = "abd";
        $c = strcmp($x, $y);
        $h = crc32($x);
    } elseif ($factory === "rp") {
        $x = hash("md5", "partition", true);
        $y = hash("md5", "partition", true);
        $c = strcmp($x, $y);
        $n = strlen($x);
    } else {
        $c = 0;
    }
}
?>
--EXPECT--
