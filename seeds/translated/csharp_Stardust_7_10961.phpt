--TEST--
Translated seed (inferredbugs-csharp: Stardust/7)
--FILE--
<?php
$u = [];
foreach (glob('/proc/net/tcp*') as $f) {
    $lines = @file($f, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) continue;
    foreach (array_slice($lines, 1) as $line) {
        $p = preg_split('/\s+/', trim($line));
        if (isset($p[3])) $u[$p[3]] = 1;
    }
}
$uptime = (int)floor(microtime(true));
if (PHP_INT_SIZE >= 8 && function_exists('hrtime')) {
    $t = hrtime(true);
    if ($t > 0) $uptime = (int)($t / 1000000000);
}
$delay = 0;
$now = (int)(microtime(true) * 1000);
$dt = $now - 1;
$ms = (int)($now - $dt);
$delay = $delay > 0 ? (int)(($delay + $ms) / 2) : $ms;
$cmds = [];
foreach (scandir('.') ?: [] as $e) {
    if ($e === '.' || $e === '..') continue;
    $n = strtolower($e);
    if ($n === 'idle' || $n === 'system' || $n === 'registry') continue;
    if (!isset($cmds[$n])) $cmds[$n] = 1;
}
echo $uptime . "\n";
echo $delay . "\n";
echo count($u) . "\n";
echo count($cmds) . "\n";
?>
--EXPECT--
