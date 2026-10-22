--TEST--
Translated seed (inferredbugs-csharp: poderosa/2)
--FILE--
<?php
$window = 3;
$left = $window;
$allowed = 0;
$events = [];
for ($i = 0; $i < 5; $i++) {
    $dataLen = 2;
    $left -= $dataLen;
    while ($left <= $window) {
        $allowed -= $window;
        $allowed += $window;
        $left += $window;
        $events[] = $left;
    }
}
echo count($events), "\n";
echo $left, "\n";
echo $allowed, "\n";
?>
--EXPECT--
