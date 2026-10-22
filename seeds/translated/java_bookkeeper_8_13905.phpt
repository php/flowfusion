--TEST--
Translated seed (inferredbugs-java: bookkeeper/8)
--FILE--
<?php

$logId = -1;
$logSizeLimit = 1024 * 1024; // 1MB
$dirs = ['./logs']; // Example directory for logs
$logChannel = null;

function createNewLog() {
    global $dirs, $logId, $logChannel;
    shuffle($dirs);
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $logFileName = dechex(++$logId) . '.log';
        $newLogFile = $dir . '/' . $logFileName;
        if (!file_exists($newLogFile)) {
            $logChannel = fopen($newLogFile, 'wb');
            fwrite($logChannel, "BKLO" . str_repeat("\0", 1020)); 
            return;
        }
    }
}

function addEntry($entry) {
    global $logChannel, $logSizeLimit;
    if (ftell($logChannel) + strlen($entry) + 4 > $logSizeLimit) {
        createNewLog();
    }
    $size = pack("N", strlen($entry));
    fwrite($logChannel, $size);
    fwrite($logChannel, $entry);
    return ($logId << 32) | ftell($logChannel);
}

function readEntry($location) {
    global $dirs;
    $entryLogId = $location >> 32;
    $pos = $location & 0xffffffff;
    $logFile = $dirs[0] . '/' . dechex($entryLogId) . '.log';
    $stream = fopen($logFile, 'rb');
    fseek($stream, $pos - 4);
    $size = fread($stream, 4);
    $entrySize = unpack("N", $size)[1];
    fseek($stream, $pos);
    $data = fread($stream, $entrySize);
    fclose($stream);
    return $data;
}

createNewLog();
$entryId = addEntry("Example Entry Data");
$data = readEntry($entryId);
echo $data;
fclose($logChannel);
--EXPECT--
