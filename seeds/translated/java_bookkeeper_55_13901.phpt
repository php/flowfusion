--TEST--
Translated seed (inferredbugs-java: bookkeeper/55)
--FILE--
<?php

$metricsOutputFrequency = 60;
$prefix = "";
$graphiteHost = "localhost:2003";
$csvDir = "/path/to/output";
$slf4jCat = null;

$metrics = [];
$reporters = [];

function initIfNecessary() {
    global $metrics;
    if ($metrics === null) {
        $metrics = [];
    }
}

function start() {
    global $metricsOutputFrequency, $prefix, $graphiteHost, $csvDir, $slf4jCat, $reporters;
    initIfNecessary();

    if ($graphiteHost) {
        $addr = explode(":", $graphiteHost);
        $reporters[] = "Graphite Reporter set for {$addr[0]}:{$addr[1]} with prefix {$prefix}";
    }
    if ($csvDir) {
        $outdir = empty($prefix) ? $csvDir : "{$csvDir}/{$prefix}";
        $reporters[] = "CSV Reporter set for directory {$outdir}";
    }
    if ($slf4jCat) {
        $reporters[] = "Slf4j Reporter set for {$slf4jCat}";
    }
    
    foreach ($reporters as $reporter) {
        echo "{$reporter}\n";
    }
}

function stop() {
    global $reporters;
    foreach ($reporters as $reporter) {
        echo "Stopping {$reporter}\n";
    }
}

start();
stop();
--EXPECT--
