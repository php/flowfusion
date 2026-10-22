--TEST--
Translated seed (inferredbugs-csharp: Shielded/3)
--FILE--
<?php
$taskCount = 10000;
$randomizr = random_int(0, PHP_INT_MAX);
for ($iter = 0; $iter < 5; $iter++) {
    $x = array_fill(0, 100, 0);
    for ($i = 0; $i < $taskCount; $i++) {
        $rnd = random_int(0, 99);
        $v = $x[$rnd];
        usleep(1000);
        $x[$rnd] = $v + 1;
    }
    $sum = array_sum($x);
    echo $sum === $taskCount ? "correct\n" : "incorrect\n";
}
?>
--EXPECT--
