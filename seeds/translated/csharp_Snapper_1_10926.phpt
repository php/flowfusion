--TEST--
Translated seed (inferredbugs-csharp: Snapper/1)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_snapshot_' . bin2hex(random_bytes(4));
@mkdir($dir, 0777, true);
$file = $dir . DIRECTORY_SEPARATOR . 'snapshot.json';

if (!file_exists($file)) {
    $snapshot = null;
} else {
    $content = file_get_contents($file);
    $snapshot = trim($content) === '' ? null : json_decode($content, true);
}

$partialId = 'part';
$value = ['x' => 1, 'y' => [true, null, 'z']];

if ($partialId === null) {
    $newSnapshotToWrite = $value;
} else {
    $rawSnapshotContent = file_exists($file) ? file_get_contents($file) : null;
    if ($rawSnapshotContent === null || trim($rawSnapshotContent) === '') {
        $newSnapshotToWrite = [$partialId => $value];
    } else {
        $newSnapshotToWrite = json_decode($rawSnapshotContent, true);
        $newSnapshotToWrite[$partialId] = $value;
    }
}

file_put_contents($file, json_encode($newSnapshotToWrite, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

if (file_exists($file)) {
    $fullSnapshot = json_decode(file_get_contents($file), true);
    $result = array_key_exists($partialId, $fullSnapshot) ? $fullSnapshot[$partialId] : null;
}

?>
--EXPECT--
