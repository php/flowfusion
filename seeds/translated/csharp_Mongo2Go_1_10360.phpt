--TEST--
Translated seed (inferredbugs-csharp: Mongo2Go/1)
--FILE--
<?php
$cmd = 'php -r ' . escapeshellarg('fwrite(STDOUT, "starting\n"); usleep(100000); fwrite(STDOUT, "waiting for connections\n");');
$descriptors = [1 => ['pipe', 'w']];
$proc = proc_open($cmd, $descriptors, $pipes);
if (is_resource($proc)) {
    stream_set_blocking($pipes[1], false);
    $ready = false;
    while (!$ready) {
        $out = stream_get_contents($pipes[1]);
        if ($out !== false && $out !== '' && strpos($out, 'waiting for connections') !== false) {
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
