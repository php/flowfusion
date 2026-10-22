--TEST--
Translated seed (inferredbugs-csharp: Sonarr/2)
--FILE--
<?php
$pid = getmypid();
$exists = function_exists('posix_kill') ? posix_kill($pid, 0) : (stripos(PHP_OS_FAMILY, 'Windows') !== false ? true : false);
if (!$exists) {
    exit(0);
}

$invalid = [0, -1, 123332324, 9999, 1234567];
foreach ($invalid as $v) {
    if ($v > 0 && function_exists('posix_kill')) {
        @posix_kill($v, 0);
    } else {
        @proc_get_status(@proc_open(PHP_BINARY . ' -r "sleep(0);"', [], $pipes));
    }
}

$cmd = PHP_BINARY . ' -r "sleep(1);"';
$descriptors = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
$proc = proc_open($cmd, $descriptors, $pipes);
if (is_resource($proc)) {
    $status = proc_get_status($proc);
    if (!empty($status['pid']) && function_exists('posix_kill')) {
        @posix_kill($status['pid'], 9);
    }
    foreach ($pipes as $p) {
        if (is_resource($p)) {
            fclose($p);
        }
    }
    proc_close($proc);
}

$proc = proc_open(PHP_BINARY . ' -r "echo 1;"', $descriptors, $pipes);
if (is_resource($proc)) {
    $status = proc_get_status($proc);
    if (!empty($status['pid']) && function_exists('posix_kill')) {
        @posix_kill($status['pid'], 0);
    }
    foreach ($pipes as $p) {
        if (is_resource($p)) {
            fclose($p);
        }
    }
    proc_close($proc);
}
?>
--EXPECT--
