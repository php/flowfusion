--TEST--
Translated seed (inferredbugs-java: Saturn/6)
--FILE--
<?php

define('UNKNOWN_PID', -1);
define('FILESEPARATOR', DIRECTORY_SEPARATOR);
define('EXECUTINGPATH', getenv('HOME') . FILESEPARATOR . '.saturn' . FILESEPARATOR . 'executing');
define('OUTPUT_PATH', getenv('HOME') . FILESEPARATOR . '.saturn' . FILESEPARATOR . 'output');
define('JOBITEMPIDSPATH', EXECUTINGPATH . FILESEPARATOR . '%s' . FILESEPARATOR . '%s' . FILESEPARATOR . '%s');
define('JOBITEMPATH', EXECUTINGPATH . FILESEPARATOR . '%s' . FILESEPARATOR . '%s');

function mkdir_recursive($path) {
    if (!file_exists($path)) {
        mkdir_recursive(dirname($path));
        mkdir($path);
    }
}

function writePidToFile($executorName, $jobName, $jobItem, $pid) {
    $dir = sprintf(JOBITEMPIDSPATH, $executorName, $jobName, $jobItem);
    $path = sprintf(JOBITEMPATH, $executorName, $jobName, $jobItem);

    mkdir_recursive($dir);
    file_put_contents($path, $pid);
}

function getFirstPidFromFile($executorName, $jobName, $jobItem) {
    $path = sprintf(JOBITEMPATH, $executorName, $jobName, $jobItem);
    if (!file_exists($path)) {
        return UNKNOWN_PID;
    }
    return (int)file_get_contents($path);
}

function isPidRunning($pid) {
    return file_exists('/proc/' . $pid);
}

function killAllChildrenByPid($pid) {
    if ($pid <= UNKNOWN_PID) {
        return;
    }
    $pids = [];
    $pids[] = $pid;

    $result = shell_exec("pgrep -P $pid");
    if ($result !== null) {
        $childPids = explode("\n", trim($result));
        $pids = array_merge($pids, $childPids);
    }

    foreach (array_reverse($pids) as $ppid) {
        if (isPidRunning($ppid)) {
            shell_exec("kill -9 $ppid");
        }
    }
}

$executorName = "executor1";
$jobName = "job1";
$jobItem = 0;
$pid = getmypid();

writePidToFile($executorName, $jobName, $jobItem, $pid);
$childPid = getFirstPidFromFile($executorName, $jobName, $jobItem);
if (isPidRunning($childPid)) {
    killAllChildrenByPid($childPid);
}

?>
--EXPECT--
