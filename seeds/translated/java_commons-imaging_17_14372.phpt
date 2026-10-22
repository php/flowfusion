--TEST--
Translated seed (inferredbugs-java: commons-imaging/17)
--FILE--
<?php

$name = $argv[1];

$sumTime = 0;
$n = 1;
for ($i = 0; $i < 10; $i++) {
    $startTime = microtime(true);

    $image = imagecreatefromstring(file_get_contents($name));
    
    $testTime = (microtime(true) - $startTime) * 1000;

    if ($i > 1) {
        $n = $i - 1;
        $sumTime += $testTime;
    }
    $avgTime = $sumTime / $n;

    $usedMemory = memory_get_usage();
    $totalMemory = memory_get_usage(true);

    if ($i == 0) {
        echo "\nProcessing file: $name\n";
        echo " image size: " . imagesx($image) . " by " . imagesy($image) . "\n\n";
        echo " time to load image    --         memory\n";
        echo " time ms      avg ms   --    used mb   total mb\n";
    }
    echo sprintf("%9.3f %9.3f    --  %9.3f %9.3f \n", $testTime, $avgTime, $usedMemory / (1024 * 1024), $totalMemory / (1024 * 1024));
    
    imagedestroy($image);
    
    usleep(1000000);
}

?>
--EXPECT--
