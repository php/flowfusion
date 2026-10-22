--TEST--
Translated seed (inferredbugs-csharp: PowerArgs/5)
--FILE--
<?php
declare(strict_types=1);

$paintRequests = [];
$sendKeys = [];
$lastKey = null;
$lastKeyPressTime = 0.0;
$keyThrottlingEnabled = true;
$minTimeBetweenKeyPresses = 0.035;
$processed = 0;
$throttled = 0;

$events = [
    'paint' => 0,
    'keyThrottled' => 0,
];

$enqueuePaint = function () use (&$paintRequests) {
    $paintRequests[] = true;
};

$handleKeyInput = function (string $key) use (&$enqueuePaint) {
    $enqueuePaint();
};

for ($i = 0; $i < 3; $i++) {
    $sendKeys[] = 'a';
}

while ($sendKeys || $paintRequests) {
    if ($sendKeys) {
        $key = array_shift($sendKeys);
        $now = microtime(true);
        if ($keyThrottlingEnabled && $key === $lastKey && ($now - $lastKeyPressTime) < $minTimeBetweenKeyPresses) {
            $throttled++;
            $events['keyThrottled']++;
        } else {
            $lastKeyPressTime = $now;
            $lastKey = $key;
            $handleKeyInput($key);
            $processed++;
        }
    }

    if ($paintRequests) {
        $paintRequests = [];
        $events['paint']++;
    }

    usleep(20000);
}

echo $processed . "\n";
echo $throttled . "\n";
echo $events['paint'] . "\n";
echo $events['keyThrottled'] . "\n";
?>
--EXPECT--
