--TEST--
Translated seed (inferredbugs-csharp: jaeger-client-csharp/2)
--FILE--
<?php
$maxTracesPerSecond = 5.4;
$rateLimiter = null;
if ($rateLimiter === null) {
    $rateLimiter = new class {
        public function checkCredit($x) {
            return false;
        }
    };
}
$calledWith = 0.0;
$shouldReturn = false;
$traceId = 1;
$op = "op";
$sampled = $rateLimiter->checkCredit(1.0);
$calledWith = 1.0;
if ($shouldReturn) {
    $isSampled = true;
} else {
    $isSampled = false;
}
$shouldReturn = true;
$sampled = $rateLimiter->checkCredit(1.0);
$calledWith = 1.0;
if ($shouldReturn) {
    $isSampled = true;
} else {
    $isSampled = false;
}
$tags = [
    "sampler.type" => "rate_limiting",
    "sampler.param" => $maxTracesPerSecond
];
echo "";
?>
--EXPECT--
