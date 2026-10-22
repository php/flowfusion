--TEST--
Translated seed (inferredbugs-csharp: SharpEDRChecker/3)
--FILE--
<?php
$items = [
    ['name' => 'php.exe', 'path' => __FILE__, 'desc' => 'PHP script', 'caption' => 'Current process', 'cmd' => implode(' ', $argv), 'pid' => getmypid(), 'ppid' => function_exists('posix_getppid') ? posix_getppid() : null],
    ['name' => 'worker', 'path' => '/tmp/EDR-Agent.bin', 'desc' => 'background service', 'caption' => 'helper', 'cmd' => '--scan edr', 'pid' => 1234, 'ppid' => 1],
];

$edrlist = ['edr', 'agent', 'scan', 'monitor'];
$summary = '';

foreach ($items as $p) {
    $all = $p['name'] . ' - ' . $p['path'] . ' - ' . $p['desc'] . ' - ' . $p['caption'] . ' - ' . $p['cmd'];
    $metadata = '';
    if ($p['path'] !== null) {
        $st = @stat($p['path']);
        $metadata = $st ? ($st['size'] . ':' . $st['mtime']) : '';
        $all .= ' - ' . $metadata;
    }
    $matches = [];
    foreach ($edrlist as $e) {
        if (stripos($all, $e) !== false) {
            $matches[] = $e;
        }
    }
    if ($matches) {
        $summary .= "\t" . $p['name'] . ' : ' . implode(', ', $matches) . "\n";
    }
}

$mods = [__FILE__, PHP_BINARY, ini_get('extension_dir') . DIRECTORY_SEPARATOR . 'standard' . PHP_SHLIB_SUFFIX];
$modSummary = '';

foreach ($mods as $m) {
    $all = $m . ' - ';
    $st = @stat($m);
    $metadata = $st ? ($st['size'] . ':' . $st['mtime']) : '';
    $all .= $metadata;
    $matches = [];
    foreach ($edrlist as $e) {
        if (stripos($all, $e) !== false) {
            $matches[] = $e;
        }
    }
    if ($matches) {
        $modSummary .= "\t" . $m . ' : ' . implode(', ', $matches) . "\n";
    }
}

echo $summary === '' ? "[+] No suspicious processes found\n" : "\nProcess Summary:\n" . $summary . "\n";
echo $modSummary === '' ? "[+] No suspicious modules found in your process\n" : "\nModload Summary:\n" . $modSummary . "\n";
?>
--EXPECT--
