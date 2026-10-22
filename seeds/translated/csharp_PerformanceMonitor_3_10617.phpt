--TEST--
Translated seed (inferredbugs-csharp: PerformanceMonitor/3)
--FILE--
<?php
$pid = getmypid();
$oldTime = 0.0;
$oldStamp = microtime(true);
$processorTotal = (int) (getenv('NUMBER_OF_PROCESSORS') ?: 1);
$cpuVals = [];
$memVals = [];
$exceptionVals = [];
$requestVals = [];
$contentionVals = [];
$gcVals = [];
$jitVals = [];

for ($i = 0; $i < 3; $i++) {
    $newTime = (float) (hrtime(true) / 1e6);
    $newStamp = microtime(true);
    $change = $newTime - $oldTime;
    $period = ($newStamp - $oldStamp) * 1000.0;
    $oldTime = $newTime;
    $oldStamp = $newStamp;
    $cpuVals[] = ['usage' => $period > 0 ? ($change / ($period * $processorTotal) * 100.0) : 0.0, 'timestamp' => $newStamp];
    $memVals[] = ['usage' => memory_get_usage(true), 'timestamp' => $newStamp];
    try {
        throw new RuntimeException("boom $i");
    } catch (Throwable $e) {
        $exceptionVals[] = ['type' => get_class($e), 'timestamp' => microtime(true)];
    }
    $requestVals[] = ['type' => 'Start', 'timestamp' => microtime(true), 'id' => $i, 'method' => 'GET', 'path' => '/'];
    $requestVals[] = ['type' => 'Stop', 'timestamp' => microtime(true), 'id' => $i];
    $contentionVals[] = ['type' => 'Start', 'timestamp' => microtime(true)];
    $contentionVals[] = ['type' => 'Stop', 'timestamp' => microtime(true)];
    $gcVals[] = ['type' => 'Triggered', 'timestamp' => microtime(true), 'id' => $i];
    $jitVals[] = ['method' => __FUNCTION__, 'timestamp' => microtime(true)];
    str_repeat("x", 10000);
}

if ($cpuVals) {
    $metricList = [
        'cpu' => $cpuVals,
        'mem' => $memVals,
        'exceptions' => $exceptionVals,
        'requests' => $requestVals,
        'contentions' => $contentionVals,
        'gc' => $gcVals,
        'jit' => $jitVals,
    ];
    echo json_encode($metricList, JSON_UNESCAPED_SLASHES), PHP_EOL;
}
?>
--EXPECT--
