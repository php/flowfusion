--TEST--
Translated seed (inferredbugs-csharp: nunit-console/3)
--FILE--
<?php
$oldOut = fopen('php://stdout', 'w');
$oldErr = fopen('php://stderr', 'w');

$workers = 0;
if (isset($argv[1]) && is_numeric($argv[1])) {
    $workers = (int)$argv[1];
} else {
    $workers = max((int)trim(shell_exec('getconf _NPROCESSORS_ONLN 2>/dev/null') ?: '1'), 2);
}

$queue = [];
if ($workers > 0) {
    for ($i = 0; $i < $workers; $i++) {
        $queue[] = "worker:$i";
    }
} else {
    $queue[] = "single";
}

foreach ($queue as $msg) {
    fwrite($oldOut, $msg . PHP_EOL);
}

fwrite($oldErr, "done" . PHP_EOL);
?>
--EXPECT--
