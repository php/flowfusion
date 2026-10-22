--TEST--
Translated seed (inferredbugs-csharp: nunit-console/4)
--FILE--
<?php
$settings = [
    'capture_stdout' => true,
    'capture_stderr' => true,
    'workers' => null,
];
$loaded = [
    'parallel_scope' => 7,
    'level_of_parallelization' => null,
];

$level = $settings['workers'] !== null ? (int)$settings['workers'] : ($loaded['level_of_parallelization'] !== null ? (int)$loaded['level_of_parallelization'] : max((int)trim(shell_exec('getconf _NPROCESSORS_ONLN 2>/dev/null') ?: '1'), 2));

$oldOut = fopen('php://stdout', 'w');
$oldErr = fopen('php://stderr', 'w');

$out = $settings['capture_stdout'] ? fopen('php://temp', 'w+') : $oldOut;
$err = $settings['capture_stderr'] ? fopen('php://temp', 'w+') : $oldErr;

for ($i = 0; $i < $level; $i++) {
    fwrite($out, "out$i\n");
    fwrite($err, "err$i\n");
}

if (($loaded['parallel_scope'] ?? null) !== null && $level > 0) {
    $loaded['parallel_scope'] &= ~1;
}

fflush($out);
fflush($err);

if ($settings['capture_stdout']) fclose($out);
if ($settings['capture_stderr']) fclose($err);

fwrite($oldOut, "");
fwrite($oldErr, "");
?>
--EXPECT--
