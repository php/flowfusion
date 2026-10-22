--TEST--
Translated seed (inferredbugs-csharp: SharpGLTF/4)
--FILE--
<?php
$files = [];
$dir = __DIR__ . '/models';
if (is_dir($dir)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $f) {
        $p = $f->getPathname();
        if (preg_match('/\.(gltf|glb)$/i', $p)) $files[] = $p;
    }
}
if (!$files) {
    $files = ['ok.gltf', 'bad.gltf', 'unsupported.gltf', 'ok.glb'];
}
sort($files);

foreach ($files as $filePath) {
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $gltfJson = $ext === 'gltf' && is_file($filePath) ? file_get_contents($filePath) : '';
    $report = null;
    if (strpos($filePath, 'unsupported') !== false) {
        $report = ['severity' => 'warning', 'unsupported' => true];
    } elseif (strpos($filePath, 'bad') !== false) {
        $report = ['severity' => 'error', 'unsupported' => false];
    } else {
        $report = ['severity' => 'info', 'unsupported' => false];
    }
    if ($report === null || !empty($report['unsupported'])) continue;
    try {
        if ($report['severity'] === 'error') {
            throw new Exception('invalid asset');
        }
        echo $filePath . PHP_EOL;
    } catch (Exception $ex) {
        if ($report['severity'] !== 'error') {
            echo $filePath . PHP_EOL;
            echo "ERROR: " . $ex->getMessage() . PHP_EOL;
        } else {
            echo $filePath . PHP_EOL;
            echo "Expected Exception: " . $ex->getMessage() . PHP_EOL;
        }
    }
}
?>
--EXPECT--
