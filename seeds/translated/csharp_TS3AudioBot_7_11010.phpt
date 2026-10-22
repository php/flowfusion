--TEST--
Translated seed (inferredbugs-csharp: TS3AudioBot/7)
--FILE--
<?php
declare(strict_types=1);

$seed = "9223372036854775807";
$volume = "+2147483648";
$position = "1:60";
$items = ["alpha", "beta", "gamma", "delta"];
$cmp = "==";

$parsed = filter_var($seed, FILTER_VALIDATE_FLOAT);
if ($parsed !== false) {
    $d0 = (float)$parsed;
    $d1 = (float)"9223372036854775808";
    $cmpResult = abs($d0 - $d1) < 1e-6;
} else {
    $cmpResult = strcmp($seed, "9223372036854775808") === 0;
}

if ($cmpResult) {
    $parts = explode(":", $position);
    if (count($parts) === 2 && ctype_digit($parts[0]) && ctype_digit($parts[1])) {
        $span = ((int)$parts[0]) * 60 + (int)$parts[1];
        if ($span >= 0) {
            $items = array_slice($items, 0, 2);
        }
    }
}

$relPos = str_starts_with($volume, "+");
$relNeg = str_starts_with($volume, "-");
$num = (float)substr($volume, ($relPos || $relNeg) ? 1 : 0);
$newVolume = $relPos ? 50.0 + $num : ($relNeg ? 50.0 - $num : $num);
if ($newVolume >= 0.0 && $newVolume <= 100.0) {
    $items[] = (string)$newVolume;
}

echo implode(",", $items), PHP_EOL;
--EXPECT--
