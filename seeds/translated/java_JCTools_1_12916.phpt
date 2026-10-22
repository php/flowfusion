--TEST--
Translated seed (inferredbugs-java: JCTools/1)
--FILE--
<?php
$qType = "SpscArrayQueue";
$qCapacity = 132000;
$burstSize = 1;
$q = array_fill(0, $qCapacity, null);
$head = 0;
$tail = 0;

for ($i = 0; $i < $burstSize; $i++) {
    $q[$tail] = 1;
    $tail = ($tail + 1) % $qCapacity;
}

$result = null;
for ($i = 0; $i < $burstSize; $i++) {
    $result = $q[$head];
    $q[$head] = null;
    $head = ($head + 1) % $qCapacity;
}
--EXPECT--
