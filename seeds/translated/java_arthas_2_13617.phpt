--TEST--
Translated seed (inferredbugs-java: arthas/2)
--FILE--
<?php

$pid = "-1";

$jvmName = getmyuid();
$pid = (string)$jvmName;

function listProcessByJps($v) {
    $result = [];
    $jps = findJps();
    if ($jps === null) {
        return $result;
    }

    $command = $v ? "$jps -v" : $jps;
    $output = [];
    exec($command, $output);

    $currentPid = (int)getmypid();
    foreach ($output as $line) {
        $pid = (int)explode(' ', trim($line))[0];
        if ($pid != $currentPid) {
            $result[$pid] = $line;
        }
    }

    return $result;
}

function findJps() {
    $javaHome = getenv('JAVA_HOME');
    $paths = ["bin/jps", "bin/jps.exe", "../bin/jps", "../bin/jps.exe"];
    foreach ($paths as $path) {
        $jpsFile = "$javaHome/$path";
        if (file_exists($jpsFile)) {
            return $jpsFile;
        }
    }
    return null;
}

$processMap = listProcessByJps(true);

if (empty($processMap)) {
    echo "Can not find java process.\n";
    exit(-1);
}

$count = 1;
foreach ($processMap as $process) {
    echo ($count === 1 ? "* [$count]: " : "  [$count]: ") . $process . "\n";
    $count++;
}

$line = trim(fgets(STDIN));
$choice = empty($line) ? 1 : (int)$line;

if ($choice <= 0 || $choice > count($processMap)) {
    exit(-1);
}

$idIter = new ArrayIterator(array_keys($processMap));
for ($i = 1; $i <= $choice; ++$i) {
    if ($i === $choice) {
        $selectedPid = $idIter->current();
        break;
    }
    $idIter->next();
}

exit($selectedPid);
--EXPECT--
