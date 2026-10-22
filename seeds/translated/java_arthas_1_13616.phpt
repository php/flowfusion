--TEST--
Translated seed (inferredbugs-java: arthas/1)
--FILE--
<?php

$pid = "-1";

$jvmName = shell_exec("ps -p " . getmypid() . " -o args=");
if ($jvmName !== null) {
    $index = strpos($jvmName, '@');
    if ($index !== false) {
        $pid = substr($jvmName, 0, $index);
    }
}

function listProcessByJps($v) {
    $result = [];
    $jps = findJps();
    if ($jps === null) {
        return $result;
    }
    $command = $v ? "$jps -v" : $jps;
    $output = shell_exec($command);
    $lines = explode("\n", trim($output));
    $currentPid = getmypid();
    foreach ($lines as $line) {
        $parts = preg_split('/\s+/', $line);
        if (count($parts) > 0) {
            $pid = intval($parts[0]);
            if ($pid !== $currentPid) {
                $result[$pid] = $line;
            }
        }
    }
    return $result;
}

function findJps() {
    $javaHome = getenv('JAVA_HOME');
    $paths = ["bin/jps", "bin/jps.exe", "../bin/jps", "../bin/jps.exe"];
    foreach ($paths as $path) {
        $jpsFile = $javaHome . DIRECTORY_SEPARATOR . $path;
        if (file_exists($jpsFile)) {
            return $jpsFile;
        }
    }
    return null;
}

$processMap = listProcessByJps(false);
if (empty($processMap)) {
    exit(-1);
}

$count = 1;
foreach ($processMap as $process) {
    echo ($count === 1 ? "* [" : "  [") . $count . "]: " . $process . PHP_EOL;
    $count++;
}

$line = trim(fgets(STDIN));
if ($line === "") {
    $choice = array_keys($processMap)[0];
} else {
    $choice = intval($line);
    if ($choice <= 0 || $choice > count($processMap)) {
        exit(-1);
    }
    $keys = array_keys($processMap);
    $choice = $keys[$choice - 1];
}

echo "Selected PID: " . $choice . PHP_EOL;
--EXPECT--
