--TEST--
Translated seed (inferredbugs-csharp: jaeger-client-csharp/3)
--FILE--
<?php
$samplingRate = 0.5;
if ($samplingRate < 0.0 || $samplingRate > 1.0) {
    throw new InvalidArgumentException("sampling rate must be between 0.0 and 1.0");
}

$traceId = PHP_INT_MAX;
$tags = [
    'sampler.type' => 'probabilistic',
    'sampler.param' => $samplingRate,
];

$sampled = (($traceId + 10) & 1) === 0;
if (!$sampled) {
    $traceId = $traceId - 20;
    $sampled = (($traceId & 1) === 0);
}

echo "";
?>
--EXPECT--
