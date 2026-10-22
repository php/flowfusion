--TEST--
Translated seed (inferredbugs-java: arthas/3)
--FILE--
<?php

$pid = "-1";

$jvmName = trim(shell_exec("ps -o pid,comm | grep -w $pid"));
if (preg_match('/^(\d+)\s/', $jvmName, $matches)) {
    $pid = $matches[1];
}

function getPid() {
    global $pid;
    return $pid;
}

function select($v) {
    $processMap = listProcessByJps($v);

    if (empty($processMap)) {
        echo "Can not find process. Try to pass pid in command line.\n";
        return -1;
    }

    $count = 1;
    foreach ($processMap as $process) {
        if ($count == 1) {
            echo "* [$count]: $process\n";
        } else {
            echo "  [$count]: $process\n";
        }
        $count++;
    }

    $line = trim(fgets(STDIN));
    if ($line === '') {
        return key($processMap);
    }

    $choice = intval($line);
    if ($choice <= 0 || $choice > count($processMap)) {
        return -1;
    }

    return array_keys($processMap)[$choice - 1];
}

function listProcessByJps($v) {
    global $pid;

    $jpsCmd = "jps" . ($v ? " -v" : "");
    $output = shell_exec($jpsCmd);
    $lines = explode("\n", $output);
    $result = [];

    foreach ($lines as $line) {
        if (preg_match('/^(\d+)\s/', $line, $matches)) {
            $currentPid = intval($matches[1]);
            if ($currentPid != $pid) {
                $result[$currentPid] = $line;
            }
        }
    }

    return $result;
}

function findJava() {
    $javaHome = getenv('JAVA_HOME');
    $paths = ["bin/java", "bin/java.exe", "../bin/java", "../bin/java.exe"];

    foreach ($paths as $path) {
        $javaFile = $javaHome . DIRECTORY_SEPARATOR . $path;
        if (file_exists($javaFile)) {
            return $javaFile;
        }
    }
    return null;
}

$v = false;
$selectedPid = select($v);
--EXPECT--
