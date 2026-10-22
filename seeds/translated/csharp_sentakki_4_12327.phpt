--TEST--
Translated seed (inferredbugs-csharp: sentakki/4)
--FILE--
<?php
declare(strict_types=1);

$timeCurrent = 0.0;
$hitStartTime = 1000.0;
$hitEndTime = 1300.0;
$headStartTime = 1000.0;
$windowMiss = 50.0;
$lane = 2;
$key1 = 1;
$action = $key1 + $lane;
$holdStartTime = null;
$totalHoldTime = 0.0;
$allJudged = false;
$auto = false;

$beginHoldAt = function(float $timeOffset) use (&$holdStartTime, $timeCurrent, $hitStartTime, $windowMiss): bool {
    if ($timeOffset < -$windowMiss) return false;
    $holdStartTime = max($timeCurrent, $hitStartTime);
    return true;
};

$endHold = function() use (&$holdStartTime, &$totalHoldTime, $timeCurrent): void {
    if ($holdStartTime !== null) $totalHoldTime += max($timeCurrent - $holdStartTime, 0.0);
    $holdStartTime = null;
};

if (!$allJudged && $action === $key1 + $lane) {
    if ($beginHoldAt($timeCurrent - $headStartTime)) {
        $holdStartTime = max($timeCurrent, $hitStartTime);
    }
}

$timeCurrent = 1100.0;

if (!$allJudged && $holdStartTime !== null && $action === $key1 + $lane) {
    $endHold();
}

$timeCurrent = 1310.0;

if ($timeCurrent > $hitEndTime) {
    $endHold();
    $duration = 300.0;
    $totalHoldRatio = $totalHoldTime / $duration;
    if ($totalHoldRatio >= 0.75 || $auto) {
        $result = 'Great';
    } elseif ($totalHoldRatio >= 0.5) {
        $result = 'Good';
    } elseif ($totalHoldRatio >= 0.25) {
        $result = 'Ok';
    } else {
        $result = 'Miss';
    }
    if (!$allJudged) {
        $allJudged = true;
    }
}

echo "";
?>
--EXPECT--
