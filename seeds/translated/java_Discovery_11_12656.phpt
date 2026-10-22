--TEST--
Translated seed (inferredbugs-java: Discovery/11)
--FILE--
<?php

function getTraceId() {
    return null; // Simulating absence of a trace ID
}

function getSpanId() {
    return null; // Simulating absence of a span ID
}

function getCustomizationMap() {
    return []; // Simulating an empty customization map
}

function isNotEmpty($value) {
    return !empty($value);
}

$traceLoggerEnabled = true; // Simulate tracing being enabled
$traceLoggerMdcKeyShown = true; // Simulate showing MDC keys
$customizationMap = getCustomizationMap();

if ($traceLoggerEnabled) {
    if (!empty($customizationMap)) {
        foreach ($customizationMap as $key => $value) {
            $output = $traceLoggerMdcKeyShown ? "$key=$value" : $value;
            echo "$output\n";
        }
    }

    $traceId = getTraceId();
    $spanId = getSpanId();

    echo ($traceLoggerMdcKeyShown ? "TRACE_ID=" : "") . (isNotEmpty($traceId) ? $traceId : '') . "\n";
    echo ($traceLoggerMdcKeyShown ? "SPAN_ID=" : "") . (isNotEmpty($spanId) ? $spanId : '') . "\n";
}

echo "--------------------------------------------------\n";
?>
--EXPECT--
