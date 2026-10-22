--TEST--
Translated seed (inferredbugs-csharp: LocalAppVeyor/1)
--FILE--
<?php
$dir = $argv[1] ?? getcwd();
$jobs = array_slice($argv, 2);

if ($dir !== '' && !is_dir($dir)) {
    fwrite(STDERR, "Repository directory '$dir' not found. Build aborted.\n");
    exit(1);
}

$yml = $dir . DIRECTORY_SEPARATOR . 'appveyor.yml';
if (!is_file($yml)) {
    fwrite(STDERR, "appveyor.yml file not found on repository path. Trying '.appveyor.yml'...\n");
    $yml = $dir . DIRECTORY_SEPARATOR . '.appveyor.yml';
    if (!is_file($yml)) {
        fwrite(STDERR, ".appveyor.yml file not found on repository path. Build aborted.\n");
        exit(1);
    }
}

$jobs = array_values(array_filter(array_map('intval', $jobs), static fn($v) => (string)$v !== '' || $v === 0));

$results = [];
if (!$jobs) {
    $results[] = 'Succeeded';
} else {
    foreach ($jobs as $job) {
        $results[] = ($job % 2 === 0) ? 'Succeeded' : 'Failed';
    }
}

if (!$results) {
    echo "Execution finished.\n";
} else {
    echo "Execution finished:\n";
    $counts = array_count_values($results);
    $parts = [];
    foreach ($counts as $k => $v) {
        $parts[] = $k . ': ' . $v;
    }
    echo '=== Execution Result: ' . implode(', ', $parts) . " ===\n";
}
?>
--EXPECT--
