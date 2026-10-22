--TEST--
Translated seed (inferredbugs-java: bookkeeper/7)
--FILE--
<?php

class EntryLogger {
    private $logId = -1;
    private $logSizeLimit;
    private $logChannel;
    private $dirs = [];
    const LOGFILE_HEADER_SIZE = 1024;
    const MB = 1024 * 1024;

    public function __construct($logSizeLimit, $dirs) {
        $this->logSizeLimit = $logSizeLimit;
        $this->dirs = $dirs;

        $header = "BKLO" . str_repeat("\0", self::LOGFILE_HEADER_SIZE - 4);
        $this->createNewLog($header);
    }

    private function createNewLog($header) {
        shuffle($this->dirs);
        if ($this->logChannel) $this->logChannel->flush();

        do {
            $this->logId++;
            $logFileName = dechex($this->logId) . ".log";
            foreach ($this->dirs as $dir) {
                $newLogFile = $dir . DIRECTORY_SEPARATOR . $logFileName;
                if (!file_exists($newLogFile)) {
                    $this->logChannel = fopen($newLogFile, 'wb');
                    fwrite($this->logChannel, $header);
                    return;
                }
            }
        } while (true);
    }

    public function addEntry($entry) {
        if (ftell($this->logChannel) + strlen($entry) + 4 > $this->logSizeLimit) {
            $this->createNewLog("BKLO" . str_repeat("\0", self::LOGFILE_HEADER_SIZE - 4));
        }
        fwrite($this->logChannel, pack("N", strlen($entry)));
        fwrite($this->logChannel, $entry);
    }

    public function shutdown() {
        if ($this->logChannel) {
            fflush($this->logChannel);
            fclose($this->logChannel);
        }
    }
}

$dirs = [sys_get_temp_dir()];
$logger = new EntryLogger(64 * 1024, $dirs);
$logger->addEntry("Example log entry data");
$logger->shutdown();
--EXPECT--
