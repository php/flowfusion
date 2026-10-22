--TEST--
Translated seed (inferredbugs-java: Chronicle-Logger/8)
--FILE--
<?php

class Logger {
    public function info($fmt, ...$args) {
        echo vsprintf($fmt, $args) . PHP_EOL;
    }
}

class Slf4jTestBase {

    static function basePath() {
        $path = sys_get_temp_dir();
        $sep = DIRECTORY_SEPARATOR;

        if (substr($path, -1) !== $sep) {
            $path .= $sep;
        }

        return $path . "chronicle-slf4j";
    }

    static function basePathWithLogger($loggerName) {
        return self::basePath() . DIRECTORY_SEPARATOR . $loggerName;
    }

    static function log($logger, $level, $fmt, ...$args) {
        switch ($level) {
            case 'INFO':
                $logger->info($fmt, ...$args);
                break;
            // Other log levels can be added as needed
            default:
                throw new Exception("Unsupported log level");
        }
    }

    static function warmup($logger) {
        for ($i = 0; $i < 10; $i++) {
            $logger->info("warmup");
        }
    }
}

class RunnableLogger implements Runnable {
    private $logger;
    private $runs;
    private $fmt;
    private $fmtBase = " > val1=%d, val2=%d, val3=%.2f";

    public function __construct($runs, $pad, $loggerName) {
        $this->logger = new Logger();
        $this->runs = $runs;
        $this->fmt = str_pad($this->fmtBase, $pad + strlen($this->fmtBase) - (4 + 8 + 8), "X");
    }

    public function run() {
        for ($i = 0; $i < $this->runs; $i++) {
            Slf4jTestBase::log($this->logger, 'INFO', $this->fmt, $i, $i * 7, $i / 16.0);
        }
    }
}

$logger = new RunnableLogger(5, 50, 'myLogger');
$logger->run();

?>
--EXPECT--
