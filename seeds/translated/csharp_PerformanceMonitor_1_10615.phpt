--TEST--
Translated seed (inferredbugs-csharp: PerformanceMonitor/1)
--FILE--
<?php
$cpuVals = [];
$memVals = [];
$exceptionVals = [];
$processorTotal = (int) (getenv('NUMBER_OF_PROCESSORS') ?: 1);
$oldTime = hrtime(true);
$oldStamp = microtime(true);
for ($i = 0; $i < 3; $i++) {
    $newTime = hrtime(true);
    $newStamp = microtime(true);
    $change = $newTime - $oldTime;
    $period = ($newStamp - $oldStamp) * 1000.0;
    $oldTime = $newTime;
    $oldStamp = $newStamp;
    $cpuVals[] = ['usage' => $period > 0 ? ($change / ($period * 1000000.0 * $processorTotal) * 100.0) : 0.0, 'timestamp' => $newStamp];
    $memVals[] = ['usage' => memory_get_usage(true), 'timestamp' => $newStamp];
    try {
        if ($i === 1) {
            throw new RuntimeException('test');
        }
    } catch (Throwable $e) {
        $exceptionVals[] = ['type' => get_class($e), 'timestamp' => microtime(true)];
    }
    usleep(1000);
}
echo json_encode(['cpu' => $cpuVals, 'mem' => $memVals, 'exceptions' => $exceptionVals], JSON_UNESCAPED_SLASHES), PHP_EOL;
--EXPECT--
