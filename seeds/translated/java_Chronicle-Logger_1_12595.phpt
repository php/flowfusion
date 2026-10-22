--TEST--
Translated seed (inferredbugs-java: Chronicle-Logger/1)
--FILE--
<?php

class Logger {
    const ERROR = 'ERROR';
    const DEBUG = 'DEBUG';

    private $logs = [];

    public function write($level, $timestamp, $threadName, $loggerName, $message, $exception = null, ...$args) {
        $this->logs[] = [
            'level' => $level,
            'ts' => $timestamp,
            'threadName' => $threadName,
            'loggerName' => $loggerName,
            'message' => $message,
            'throwable' => $exception ? $exception->getMessage() : null,
            'args' => $args
        ];
    }

    public function getLogs() {
        return $this->logs;
    }
}

function basePath() {
    $path = sys_get_temp_dir();
    $sep = DIRECTORY_SEPARATOR;

    if (!str_ends_with($path, $sep)) {
        $path .= $sep;
    }

    return $path . 'php-logger' . $sep;
}

$logger = new Logger();
$timestamp = (int)microtime(true) * 1000;
$logger->write(
    Logger::ERROR,
    $timestamp,
    getmypid(),
    'Logger',
    'Test message',
    new Exception('Test exception'),
    10,
    12.1
);

$logger->write(
    Logger::DEBUG,
    $timestamp,
    getmypid(),
    'Logger',
    'Test debug message'
);

$logs = $logger->getLogs();
foreach ($logs as $log) {
    if ($log['level'] === Logger::ERROR) {
        $expectedArgs = [10, 12.1];
        if ($log['ts'] > $timestamp || $log['throwable'] !== 'Test exception' || $log['args'] !== $expectedArgs) {
            exit(1);
        }
    } elseif ($log['level'] === Logger::DEBUG) {
        if ($log['message'] !== 'Test debug message') {
            exit(1);
        }
    }
}

exit(0);
--EXPECT--
