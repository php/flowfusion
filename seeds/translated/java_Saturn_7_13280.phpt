--TEST--
Translated seed (inferredbugs-java: Saturn/7)
--FILE--
<?php

$executorName = 'exampleExecutor';
$jobName = 'exampleJob';
$jobItem = 1;
$pid = 12345;

$home = getenv("HOME");
$executingPath = $home . DIRECTORY_SEPARATOR . '.saturn' . DIRECTORY_SEPARATOR . 'executing';
$outputPath = $home . DIRECTORY_SEPARATOR . '.saturn' . DIRECTORY_SEPARATOR . 'output';

$jobItemPidsPath = sprintf('%s%s%s%s%s%sPIDS', $executingPath, DIRECTORY_SEPARATOR, $executorName, DIRECTORY_SEPARATOR, $jobName, DIRECTORY_SEPARATOR);
$jobItemPath = sprintf('%s%s%s%s', $executingPath, DIRECTORY_SEPARATOR, $executorName, DIRECTORY_SEPARATOR, $jobName);
$jobItemPidPath = sprintf('%s%sPIDS%s%d', $jobItemPidsPath, DIRECTORY_SEPARATOR, $pid);

if (!is_dir($jobItemPidsPath)) {
    mkdir($jobItemPidsPath, 0777, true);
}
file_put_contents($jobItemPidPath, (string)$pid);

function getPidsFromFile($executorName, $jobName, $jobItem) {
    $pids = [];
    $path = sprintf('%s/%s/%s/PIDS', $executorName, $jobName, $jobItem);
    if (is_dir($path)) {
        $files = scandir($path);
        foreach ($files as $file) {
            if (is_numeric($file)) {
                $pids[] = (int)$file;
            }
        }
    }
    return $pids;
}

function isPidRunning($pid) {
    return file_exists("/proc/$pid");
}

$pids = getPidsFromFile($executorName, $jobName, $jobItem);
foreach ($pids as $pid) {
    if (isPidRunning($pid)) {
        echo "Killing PID: $pid\n";
        // Assume kill operation here
        // posix_kill($pid, SIGKILL);
    }
}
--EXPECT--
