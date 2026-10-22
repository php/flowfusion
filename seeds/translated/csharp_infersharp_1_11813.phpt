--TEST--
Translated seed (inferredbugs-csharp: infersharp/1)
--FILE--
<?php
$dir = sys_get_temp_dir() . '/php_bug_' . bin2hex(random_bytes(4));
mkdir($dir);
$f = $dir . '/data.json';
file_put_contents($f, json_encode([
    ['severity' => 'error', 'bug_type' => 'X', 'procedure' => 'p'],
    ['severity' => 'warning', 'bug_type' => 'THREAD_SAFETY_VIOLATION', 'procedure' => 'q'],
    ['severity' => 'warning', 'bug_type' => 'X', 'procedure' => 'p'],
]));
$j = json_decode(file_get_contents($f), true);
foreach ($j as $bug) {
    $s = $bug['severity'] ?? null;
    if ($s === 'error') {
        if (($bug['bug_type'] ?? null) === 'X' && ($bug['procedure'] ?? null) === 'p') break;
    } elseif ($s === 'warning') {
        $t = $bug['bug_type'] ?? null;
        if ((($t === 'X') || ($t !== 'THREAD_SAFETY_VIOLATION')) && ($bug['procedure'] ?? null) === 'p') break;
    }
}
unlink($f);
rmdir($dir);
echo "\n";
?>
--EXPECT--
