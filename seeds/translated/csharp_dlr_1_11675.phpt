--TEST--
Translated seed (inferredbugs-csharp: dlr/1)
--FILE--
<?php
$data = "héllo\0";
$len = strpos($data, "\0");
if ($len === false) $len = strlen($data);
$part = substr($data, 0, $len);
$empty = "";
$prefix = substr($data, 0, 2);
$suffix = substr($data, 2);

$hash = function ($s) {
    $hash1 = 5381;
    $hash2 = 5381;
    $n = strlen($s);
    for ($i = 0; $i < $n; $i += 2) {
        $hash1 = (($hash1 << 5) + $hash1) ^ ord($s[$i]);
        if ($i + 1 >= $n) break;
        $hash2 = (($hash2 << 5) + $hash2) ^ ord($s[$i + 1]);
    }
    return $hash1 + ($hash2 * 1566083941);
};

$h1 = $hash($part);
$h2 = $hash($prefix);
$h3 = $hash($suffix);

$pos1 = strpos($part, "l");
$pos2 = strpos($part, "x");
$last = strrpos($part, "l");

$eq1 = ($part === $data);
$eq2 = ($prefix === substr($part, 0, 2));
$eq3 = ($suffix === substr($part, 2));

echo $part, "\n", $h1, "\n", $h2, "\n", $h3, "\n", $pos1, "\n", var_export($pos2, true), "\n", $last, "\n", ($eq1 ? "1" : "0"), ($eq2 ? "1" : "0"), ($eq3 ? "1" : "0"), "\n";
?>
--EXPECT--
