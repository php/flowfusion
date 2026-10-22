--TEST--
Translated seed (inferredbugs-csharp: jitterphysics/4)
--FILE--
<?php
class Body {
    public array $bb;
    public function __construct(array $bb) { $this->bb = $bb; }
}

$bodyList = [];
$swapOrder = false;

$addBody = function (Body $body) use (&$bodyList) {
    foreach ($bodyList as $b) if ($b === $body) return;
    $bodyList[] = $body;
};

$removeBody = function (Body $body) use (&$bodyList) {
    foreach ($bodyList as $i => $b) {
        if ($b === $body) { array_splice($bodyList, $i, 1); return true; }
    }
    return false;
};

$intersects = function (array $a, array $b): bool {
    return !($a[1] < $b[0] || $b[1] < $a[0]);
};

$raycastBody = function (Body $body, float $origin, float $dir, ?float &$fraction): bool {
    $min = $body->bb[0];
    $max = $body->bb[1];
    if ($dir == 0.0) return $origin >= $min && $origin <= $max ? ($fraction = 0.0) === 0.0 : false;
    $t1 = ($min - $origin) / $dir;
    $t2 = ($max - $origin) / $dir;
    $tmin = min($t1, $t2);
    $tmax = max($t1, $t2);
    if ($tmax < 0.0) return false;
    $fraction = $tmin >= 0.0 ? $tmin : $tmax;
    return true;
};

$detect = function (Body $a, Body $b) use (&$swapOrder) {
    $swapOrder = !$swapOrder;
};

$detectAll = function (bool $multiThreaded) use (&$bodyList, &$swapOrder, $intersects, $detect) {
    $count = count($bodyList);
    for ($i = 0; $i < $count; $i++) {
        for ($e = $i + 1; $e < $count; $e++) {
            if ($intersects($bodyList[$i]->bb, $bodyList[$e]->bb)) {
                if ($swapOrder) $detect($bodyList[$i], $bodyList[$e]);
                else $detect($bodyList[$e], $bodyList[$i]);
                $swapOrder = !$swapOrder;
            }
        }
    }
};

for ($i = 0; $i < 40; $i++) $addBody(new Body([$i, $i + 1]));
$addBody($bodyList[0]);
$removeBody($bodyList[1]);

$detectAll(false);

$bestBody = null;
$bestFraction = INF;
$origin = 0.25;
$dir = 1.0;

foreach ($bodyList as $b) {
    $f = null;
    if ($raycastBody($b, $origin, $dir, $f) && $f < $bestFraction) {
        $bestFraction = $f;
        $bestBody = $b;
    }
}

echo $bestBody !== null ? "" : "";
?>
--EXPECT--
