--TEST--
Translated seed (inferredbugs-java: Java-Lang/5)
--FILE--
<?php
$RECORDS = isset($argv[1]) ? (int)$argv[1] : 128;
$RECORD_SIZE = isset($argv[2]) ? (int)$argv[2] : 64;
$WARMUP = isset($argv[3]) ? (int)$argv[3] : 12000;
$RUNS = isset($argv[4]) ? (int)$argv[4] : 5000000;

$toggleTo = isset($argv[5]) ? (bool)$argv[5] : false;
$tmpFile = tempnam(sys_get_temp_dir(), 'lock-test-');
$fileHandle = fopen($tmpFile, 'c+b');
ftruncate($fileHandle, $RECORDS * $RECORD_SIZE);

$start = 0;
for ($i = -($WARMUP / $RECORDS); $i < (($RUNS + $RECORDS - 1) / $RECORDS); $i++) {
    if ($i === 0) {
        $start = microtime(true);
        echo "Started\n";
    }

    for ($j = 0; $j < $RECORDS; $j++) {
        $recordOffset = $j * $RECORD_SIZE;
        for ($t = 9999; $t >= 0; $t--) {
            if ($t === 0 && $i >= 0) {
                echo "Didn't toggle in time !??\n";
                exit(1);
            }
            if ($t === 0) {
                echo "waiting\n";
                $t = 9999;
                usleep(200000);
            }
            $lockPosition = $recordOffset; 
            flock($fileHandle, LOCK_EX); 
            fseek($fileHandle, $recordOffset + 8);
            $flag = fread($fileHandle, 1) === "\x01";
            if ($flag === $toggleTo) {
                if ($t % 100 === 0) echo "j: $j is " . ($flag ? 'true' : 'false') . "\n";
                flock($fileHandle, LOCK_UN);
                continue;
            }
            fseek($fileHandle, $recordOffset + 8);
            fwrite($fileHandle, $toggleTo ? "\x01" : "\x00");
            flock($fileHandle, LOCK_UN);
            break;
        }
    }
}
$time = microtime(true) - $start;
$toggles = (($RUNS + $RECORDS - 1) / $RECORDS) * $RECORDS * 2;
printf("Toggled %,d times with an average delay of %,d ns\n", $toggles, ($time / $toggles) * 1e9);
fclose($fileHandle);
unlink($tmpFile);
?>
--EXPECT--
