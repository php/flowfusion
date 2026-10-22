--TEST--
Translated seed (inferredbugs-csharp: PlexCleaner/1)
--FILE--
<?php
$settings = __DIR__ . '/settings.json';
file_put_contents($settings, json_encode(['SchemaVersion' => 1, 'MonitorOptions' => ['FileRetryCount' => 0, 'FileRetryWaitTime' => 0], 'ToolsOptions' => ['AutoUpdate' => false]]));
$dir = __DIR__ . '/tmp_' . bin2hex(random_bytes(4));
mkdir($dir);
file_put_contents($dir . '/a.txt', 'x');
file_put_contents($dir . '/b.txt', 'y');
$files = ['"' . $dir . '"', '"' . $dir . '/a.txt"', '"' . $dir . '/"'];
$folders = [];
$dirInfos = [];
$fileInfos = [];
foreach ($files as $path) {
    $path = trim($path, '"');
    if (is_dir($path)) {
        $folders[] = $path;
        $dirInfos[] = $path;
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $item) {
            if ($item->isDir()) {
                $dirInfos[] = $item->getPathname();
            } else {
                $fileInfos[] = $item->getPathname();
            }
        }
    } elseif (file_exists($path)) {
        $fileInfos[] = $path;
    }
}
echo count($dirInfos) . ":" . count($fileInfos) . PHP_EOL;
?>
--EXPECT--
