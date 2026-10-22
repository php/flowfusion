--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/32)
--FILE--
<?php

class CountingOutputStream {
    public $bytes = 0;

    public function write($data) {
        $this->bytes += strlen($data);
    }
}

function dumpQueueFile($filePath, $outputStream, $maxBytes) {
    if (!file_exists($filePath) || !is_readable($filePath)) {
        return;
    }

    $handle = fopen($filePath, 'rb');
    if ($handle) {
        $bytesRead = 0;
        while (($data = fread($handle, 8192)) !== false && $bytesRead < $maxBytes) {
            $outputStream->write($data);
            $bytesRead += strlen($data);
        }
        fclose($handle);
    }
}

$tempDir = sys_get_temp_dir() . '/' . uniqid('DumpQueueMainTest_', true);
mkdir($tempDir);

$queueFile = $tempDir . '/queue.dat';
file_put_contents($queueFile, "first\nlast\n");
chmod($queueFile, 0444); // Set the file to read-only

$countingOutputStream = new CountingOutputStream();
dumpQueueFile($queueFile, $countingOutputStream, PHP_INT_MAX);

echo "Bytes written: " . $countingOutputStream->bytes . "\n";
--EXPECT--
