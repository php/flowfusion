--TEST--
Translated seed (inferredbugs-csharp: Mongo2Go/3)
--FILE--
<?php
$cmd = PHP_OS_FAMILY === 'Windows' ? 'cmd /c echo waiting for connections' : 'sh -c \'printf "waiting for connections\n"\'';
$descriptors = [1 => ['pipe', 'w']];
$proc = proc_open($cmd, $descriptors, $pipes);
if (is_resource($proc)) {
    $ready = false;
    stream_set_blocking($pipes[1], false);
    $deadline = microtime(true) + 5;
    while (!$ready && microtime(true) < $deadline) {
        $line = fgets($pipes[1]);
        if ($line !== false && strpos($line, 'waiting for connections') !== false) {
            $ready = true;
        } else {
            usleep(100000);
        }
    }
    fclose($pipes[1]);
    proc_close($proc);
}
?>
--EXPECT--
