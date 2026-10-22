--TEST--
Translated seed (inferredbugs-java: arthas/4)
--FILE--
<?php

$defaultTelnetPort = 3658;
$defaultHttpPort = 8563;
$defaultTargetIp = "127.0.0.1";
$arthasLibDir = getenv("HOME") . '/.arthas/lib';

$pid = -1;
$targetIp = $defaultTargetIp;
$telnetPort = $defaultTelnetPort;
$httpPort = $defaultHttpPort;

$options = getopt("p:t:h:");

if (isset($options['p'])) {
    $pid = intval($options['p']);
}
if (isset($options['t'])) {
    $telnetPort = intval($options['t']);
}
if (isset($options['h'])) {
    echo "Usage: php arthas-boot.php -p <pid> -t <telnet-port>\n";
    exit(0);
}

if ($telnetPort > 0) {
    $telnetPortPid = findTcpListenProcess($telnetPort);
    if ($telnetPortPid > 0 && $pid != $telnetPortPid) {
        echo "Target process $pid is not using port $telnetPort.\n";
        exit(1);
    }
}

if ($pid < 0) {
    echo "Please select a valid PID.\n";
    exit(1);
}

$arthasHomeDir = findArthasHome($arthasLibDir);
if (!$arthasHomeDir) {
    echo "Arthas home not found.\n";
    exit(1);
}

startArthasCore($pid, $arthasHomeDir, $telnetPort, $httpPort);

function findTcpListenProcess($port) {
    $output = shell_exec("lsof -ti tcp:$port");
    return $output ? intval(trim($output)) : -1;
}

function findArthasHome($libDir) {
    return is_dir($libDir) ? $libDir : null;
}

function startArthasCore($pid, $arthasHomeDir, $telnetPort, $httpPort) {
    $command = "php " . $arthasHomeDir . "/arthas-core.php -pid $pid -telnet-port $telnetPort -http-port $httpPort";
    echo "Starting arthas-core with command: $command\n";
    shell_exec($command);
}
--EXPECT--
