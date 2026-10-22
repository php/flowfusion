--TEST--
Translated seed (inferredbugs-java: Kayak/23)
--FILE--
<?php

class LogFile {
    private $compressed;
    private $file;
    private $inputStream;
    private $description = '';
    private $platform = '';
    private $deviceAlias = [];
    
    public function __construct($file) {
        $this->file = $file;
        $this->compressed = endsWith($file, '.log.gz');
        $this->inputStream = $this->compressed ? gzopen($file, 'rb') : fopen($file, 'rb');
        $this->parseHeader();
    }

    private function parseHeader() {
        while (($line = $this->readLine()) !== false) {
            if (preg_match('/DESCRIPTION "(.*)"/', $line, $matches)) {
                $this->description = $matches[1];
            } elseif (preg_match('/PLATFORM (.*)/', $line, $matches)) {
                $this->platform = trim($matches[1]);
            } elseif (preg_match('/DEVICE_ALIAS ([A-Za-z0-9]+) ([a-z0-9]{1,16})/', $line, $matches)) {
                $this->deviceAlias[trim($matches[2])] = trim($matches[1]);
            } elseif (trim($line) !== '') {
                break;
            }
        }
        gzclose($this->inputStream);
    }

    private function readLine() {
        return $this->compressed ? gzgets($this->inputStream) : fgets($this->inputStream);
    }
}

function endsWith($haystack, $needle) {
    return substr($haystack, -strlen($needle)) === $needle;
}

$file = 'path/to/your/logfile.log.gz'; // Set this to the appropriate path of your log file
$logFile = new LogFile($file);
--EXPECT--
