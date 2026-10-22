--TEST--
Translated seed (inferredbugs-java: commons-csv/43)
--FILE--
<?php

$props = [
    'PHP_VERSION',                   
    'PHP_SAPI',                      
    'OS',                             
    'ARCH',                           
    'PHP_OS',                         
];

$max = 10;
$num = 0;
$elapsedTimes = [];

$argc = $argc ?? count($argv);
if ($argc > 1) {
    $max = (int)$argv[1];
}

$tests = $argc > 2 ? array_slice($argv, 2) : ['file', 'split'];

foreach ($props as $p) {
    echo "{$p}=" . constant($p) . PHP_EOL;
}
echo "Max count: {$max}" . PHP_EOL . PHP_EOL;

foreach ($tests as $test) {
    if ($test === 'file') {
        testReadBigFile(false);
    } elseif ($test === 'split') {
        testReadBigFile(true);
    }
}

function testReadBigFile($split) {
    global $max, $num, $elapsedTimes;
    for ($i = 0; $i < $max; $i++) {
        $in = fopen("worldcitiespop.txt", "r");
        $t0 = microtime(true);
        $s = readAll($in, $split);
        fclose($in);
        show($split ? "file+split" : "file", $s, $t0);
    }
    showAverage();
}

function readAll($in, $split) {
    $count = 0;
    $fields = 0;
    while (($record = fgets($in)) !== false) {
        $count++;
        $fields += $split ? count(explode(",", trim($record))) : 1;
    }
    return new Stats($count, $fields);
}

function show($msg, $s, $start) {
    global $num, $elapsedTimes;
    $elapsed = (microtime(true) - $start) * 1000;
    printf("%-20s: %5dms %d lines %d fields\n", $msg, $elapsed, $s->count, $s->fields);
    $elapsedTimes[$num++] = $elapsed;
}

function showAverage() {
    global $num, $elapsedTimes;
    $tot = 0;
    if ($num > 1) {
        for ($i = 1; $i < $num; $i++) {
            $tot += $elapsedTimes[$i];
        }
        printf("%-20s: %5dms\n\n", "Average(not first)", ($tot / ($num - 1)));
    }
    $num = 0;
}

class Stats {
    public $count;
    public $fields;

    function __construct($c, $f) {
        $this->count = $c;
        $this->fields = $f;
    }
}
--EXPECT--
