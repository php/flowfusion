--TEST--
Translated seed (inferredbugs-csharp: json-everything/1)
--FILE--
<?php
$lowerBound = -1000;
$upperBound = 1000;
$requireMultiple = [1];
$requireNotMultiple = [];

$gcf = function ($a, $b) use (&$gcf) {
    while ($b != 0) {
        $t = $b;
        $b = fmod($a, $b);
        $a = $t;
    }
    return $a;
};

$lcm2 = function ($a, $b) use (&$gcf) {
    return ($a / $gcf($a, $b)) * $b;
};

$lcm = function ($nums) use (&$lcm2) {
    $n = count($nums);
    if ($n === 0) return 1;
    if ($n === 1) return $nums[0];
    $first = $lcm2($nums[0], $nums[1]);
    for ($i = 2; $i < $n; $i++) $first = $lcm2($first, $nums[$i]);
    return $first;
};

$period = (int)$lcm(array_merge($requireNotMultiple, $requireMultiple, [1]));
if ($period === 1) {
    $value = random_int($lowerBound, $upperBound);
} else {
    $range = $upperBound - $lowerBound;
    $lowBound = $range < $period ? $lowerBound % $period : $lowerBound;
    $highBound = $range < $period ? $upperBound % $period : $upperBound;
    $multipleOf = $lcm($requireMultiple);
    $offset = 0;
    if (count($requireNotMultiple) !== 0) {
        $nonMultiples = [];
        for ($x = 0; $x < $period; $x++) {
            $ok = true;
            foreach ($requireNotMultiple as $n) {
                if ($x % $n == 0) {
                    $ok = false;
                    break;
                }
            }
            if ($ok && $x % $multipleOf == 0 && $lowBound <= $x && $x <= $highBound) $nonMultiples[] = $x;
        }
        if (count($nonMultiples) === 0) exit(0);
        $offset = $nonMultiples[random_int(0, count($nonMultiples) - 1)];
    }
    $scaledRange = intdiv($upperBound - $lowerBound, $period);
    $lowOffset = intdiv($lowerBound, $period) * $period;
    $value = random_int(0, $scaledRange) * $period + $lowOffset + $offset;
    if ($value > $upperBound) $value -= $period;
    if ($lowerBound > $value) $value += $period;
}

echo $value, PHP_EOL;
?>
--EXPECT--
